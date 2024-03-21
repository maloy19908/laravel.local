<?php

namespace App\Imports;

use App\Models\Category;
use App\Models\Client;
use App\Models\Product;
use App\Models\ProductField;
use App\Models\ProductUniqTitle;
use App\Models\Town;
use App\Services\ProductImportExportModifier;
use Arr;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ProductAvitoImport implements ToCollection, WithHeadingRow, WithMultipleSheets, WithChunkReading, ShouldQueue{

    public $category;
    public $product;
    public $userId;
    public $modifier;
    public function __construct($userId) {
        $this->userId = $userId;
        $this->modifier = ProductImportExportModifier::getInstance();
    }
    private function updateOrCreateCategory($name, $type, $parentId) {
        return Category::updateOrCreate(['name' => $name], ['type' => $type, 'parent_id' => $parentId]);
    }
    
    private function explAddr($address, $del = ',') {

        $keywords = preg_split("/[$del]+/", $address);
        foreach ($keywords as $keyword) {
            $query = Town::where('name', trim($keyword));
            if (isset($query->first()->id)) {
                return $query->first()->id;
            }
        }
    }
    public function collection(Collection $rows) {
        // --------------------------------УДАЛЕНИЕ ЕСЛИ НЕТ В ФАЙЛЕ    
        // $productsFromDb = DB::table('products')->where('user_id', $this->userId)->pluck('avito_id');
        // $productsFromExcel = $rows->pluck('avitoid');
        // $deletedProducts = $productsFromDb->diff($productsFromExcel);
        // foreach ($deletedProducts as $id) {
        //     DB::table('products')
        //     ->where('avito_id', $id)
        //     ->where('user_id', $this->userId)
        //     ->update(['deleted_at' => now()]);
        // }
        // ----------------------------------------------------------------------------
        foreach ($rows as $row) {
            $arr = [];
            $arr = Arr::add($arr, 'category', $row['category']);
            $arr = Arr::add($arr, 'servicetype', $row['servicetype']);
            $arr = Arr::add($arr, 'servicesubtype', $row['servicesubtype']);
            $arr = Arr::add($arr, 'goodstype', $row['goodstype']);
            $arr = Arr::add($arr, 'goodssubtype', $row['goodssubtype']);
            $arr = Arr::add($arr, 'bulkmaterialtype', $row['bulkmaterialtype']);

            $categories = Arr::whereNotNull($arr);

            foreach ($categories as $key => $value) {
                if ($key === 'category') {
                    $this->category = $this->updateOrCreateCategory($value, $key, 0);
                } else {
                    $this->category = $this->updateOrCreateCategory($value, $key, $this->category->id);
                }
            }

            // ---------------------------------------------------------------------------- Создание Уникального клиента из номера
            $v = Validator::make($row->toArray(), [
                'contactphone' => 'unique:clients,phone_personal',
            ]);
            if (!$v->fails()) {

                Client::create([
                    'name' => $row['managername'],
                    'phone_personal' => $row['contactphone'],
                    'user_id' => $this->userId,
                ]);
            }
            
            $client = Client::where('phone_personal', $row['contactphone'])->first();
            $productUniqTitle = ProductUniqTitle::updateOrCreate([
                'title' => $row['title'],
            ]);
            $myId = $row['id'];
            $avitoId = $row['avitoid'];
            $product = Product::where('avito_id', $avitoId)
            ->orWhere('my_id', $myId)
            ->first();
            //----------------------------------------------------------------------------
            if ($product) {
                // Если вознкнут проблеммы с datebegin нужно разобрать 
                // сейчас не загружается если есть в файле а берет из базы 
                if (
                    $product->avito_id == $avitoId && $product->my_id == $myId
                    ||
                    $product->my_id == $myId && empty($product->avito_id) && !empty($avitoId)
                ) {
                    if ($this->userId !== $product->user_id) {
                        return redirect()->back()->with('danger', 'Вы пытаетесь загрузить то что уже есть у других');
                    }
                    
                    $product->update([
                        'avito_id' => $avitoId ?? '',
                        'client_id' => $client->id ?? null,
                        'product_uniq_title_id' => $productUniqTitle->id ?? null,
                        'category_id' => $this->category->id ?? null,
                        'address_street' => $row['address'] ?? null,
                        'productStatus' => (empty($product->dateBegin)) ? $row['avitostatus'] : $product->productStatus,
                    ]);
                    
                }
            } else {
                if (empty($avitoId)) {
                    continue;
                }
                // Создать новую запись товара
                $product = new Product([
                    'my_id' => $myId,
                    'avito_id' => $avitoId,
                    'client_id' => $client->id ?? null,
                    'product_uniq_title_id' => $productUniqTitle->id ?? null,
                    'category_id' => $this->category->id ?? null,
                    'address_street' => $row['address'] ?? null,
                    'productStatus' => $row['avitostatus'] ?? null,
                    'user_id' => $this->userId,
                ]);
                $product->save();
            }
            // ----------------------------------------------------------------------------

            $options = $row->toArray();

            $ProductFields = ProductField::updateOrCreate(
                ['product_id' => $product->id],
                ['options' => $options]
            );

            $description = $this->modifier->importFromMaskToShortcode($product);

            $updatedProductFields = ProductField::where('product_id', $product->id)->first();
            if ($updatedProductFields) {
                $updatedOptions = $updatedProductFields->options;
                $updatedOptions['description'] = $description;
                $updatedProductFields->update([
                    'options' => $updatedOptions
                ]);
            }

        }
        return redirect()->back()->with('success', 'Загрузка завершена');
    }

    public function sheets(): array {

        return [
            0 => new ProductAvitoImport($this->userId),
        ];
    }
    public function startRow(): int {
        return 1;
    }

    public function batchSize(): int {
        return 200;
    }

    public function chunkSize(): int {
        return 50;
    }
}
