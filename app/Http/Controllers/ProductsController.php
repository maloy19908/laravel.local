<?php

namespace App\Http\Controllers;

use App\Exports\ProductAvitoExport;
use App\Exports\ProductAvitoExportForClient;
use App\Exports\ProductAvitoExportForUser;
use App\Http\Filters\ProductsAvitoFilter;
use App\Imports\ProductAvitoImport;
use App\Imports\TestImport;
use App\Jobs\AfterImportProductAvito;
use App\Jobs\ImportTestJob;
use App\Models\Category;
use App\Models\ProductUniqTitle;
use App\Models\Product;
use App\Models\Client;
use App\Models\Nomenclature;
use App\Models\ProductField;
use App\Models\Shortcode;
use App\Models\Town;
use App\Services\ProductDescriptionModifier;
use App\Services\ProductImportExportModifier;
use Arr;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ProductsController extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createNpriceLink(Request $request) {
        // НУЖНО ВМЕСТО PRICE_ID NOMENCLATURE_ID
        $request->validate([
            'client_id' => 'required|integer|exists:clients,id',
            'nomenclature_id' => 'required|integer',
            'category_id' => 'required|integer|exists:categories,id',
            'product_id' => 'required|array',
            'product_id.*' => 'required|integer|exists:products,id',
        ]);
        $products = Product::withTrashed()->where('client_id', $request->client_id)
            ->whereIn('id', $request->product_id)
            ->get();
        foreach ($products as $product) {
            if ($request->nomenclature_id == -1) {
                $product->update(['nomenclature_id' => null]);
            } else {
                $product->update(['nomenclature_id' => $request->nomenclature_id]);
            }
        }
        return redirect(route('clients.show', [
            'client' => $request->client_id,
            'categoryId' => $request->category_id,
        ]))->with('success', 'успех');
    }

    public function index(Request $request) {
        $data = $request->query();
        $filter = app()->make(ProductsAvitoFilter::class, ['queryParams' => array_filter($data)]);
        $products  = Product::with('productUniqTitle', 'category', 'town')->filter($filter)->paginate(50)->withQueryString();
        return view('products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request) {
        return view('products.create', [
            'avitomyid' => $request['avitomyid'],
            'title' => $request['title'],
            'description' => $request['description'],
            'address' => $request['address'],
            'contactphone' => $request['contactphone'],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Product $product, Request $request) {
        $request->validate([
            'my_id' => 'required|unique:products,my_id',
            'title' => 'required|string',
            'description' => 'required|string',
            'address' => '',
            'contactphone' => '',
            'client_id' => 'required|integer|exists:clients,id',
            'category_id' => 'required|integer|exists:categories,id',
        ]);
        if (ProductUniqTitle::where('title', $request->title)->first()) {
            // Если есть такой тайтл
            $product->create([
                'my_id' => $request->my_id,
                'client_id' => $request->client_id,
                'category_id' => $request->category_id,
                'product_uniq_title_id' => ProductUniqTitle::where('title', $request->title)->first()->id,
                'user_id' => Auth::id(),
            ]);
            return back()->with('success', 'успех');
        }

        // Когда попали сюда нужно сохранять все данные category,goodstype,goodssubtype,bulkmaterialtype ......
        $currentCat = Category::find($request->category_id);
        $parentCats = $currentCat->parents->reverse()->push($currentCat);
        $ProductUniqTitle = new ProductUniqTitle;
        $ProductUniqTitle->title = $request->title;
        $ProductUniqTitle->save();
        $newProduct = $product->create([
            'my_id' => $request->my_id,
            'client_id' => $request->client_id,
            'category_id' => $request->category_id,
            'product_uniq_title_id' => $ProductUniqTitle->id,
            'user_id' => Auth::id(),

        ]);
        // Нужно сделать добавление  второго телефона продукту
        $productField = new ProductField;
        $productField->product_id = $newProduct->id;
        $productField->options = [
            //'address'=> $request->address,
            //'contactphone'=> $request->contactphone,
            'description' => $request->description,
        ];
        foreach ($parentCats->pluck('name', 'type') as $type => $name) {
            $productField->options = Arr::add($productField->options, $type, $name);
        }
        $productField->save();
        return back()->with('success', 'успех');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $products
     * @return \Illuminate\Http\Response
     */
    public function show(Product $products) {
        __METHOD__;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $products
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {


        $product = Product::with('shortcodes', 'client', 'town', 'category', 'productField')->where('id', $id)->withTrashed()->firstOrFail();
        $towns = Town::get();
        $export = ProductImportExportModifier::getInstance();
        $export->exportFromShortcode($product);

        $categories = Category::with('children')->where('parent_id', 0)->get();
        return view('products.edit', compact('product', 'categories', 'towns'));
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $products
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id, ProductImportExportModifier $modifier) {
        /* 
        Нужно проверять второй телефон Product и он не должен быть в таблице FIeld
        */
        $product = Product::where('id', $id)->withTrashed()->firstOrFail();
        $productAll = Product::where('category_id', $product->category_id)->withTrashed()->get();
        $request->validate([
            'my_id' => 'required|string',
            'title' => 'required|string',
            'category' => 'required',
            'goodstype' => '',
            'goodssubtype' => '',
            'bulkmaterialtype' => '',
            'parent_id' => 'string|exists:towns,id',
        ]);

        // Изменяет категорию для всех однотипных товаров у которых одинаковый fild
        foreach ($productAll as $productOne) {
            if ($request->category == 0) {
                return back()->with('warning', 'не может быть без категории');
            }
            $productOne->update(['category_id' => $request->category]);
        }
        if ($request->has('soft_delete')) {
            if ($request->input('soft_delete')) {
                $product->delete(); // Soft delete the product
            }
        } else {
            //$product->productStatus = 'Активно'; // Soft delete the product
            $product->restore(); // Restore the product
        }
        $product->update([
            'my_id' => $request->my_id,
            'town_id' => $request->parent_id ?? $product->town_id,
            //'category_id' => $request->category,
        ]);
        $currentCat = Category::find($request->category);

        $parentCats = $currentCat->parents->reverse()->push($currentCat);

        $productField = $product->productField;
        $productFieldArr = $productField->toArray();
        $ProductUniqTitle = $product->ProductUniqTitle;
        $ProductUniqTitle->title = $request->title;
        $productFieldArr['options']['address'] ?: $request->parent_id;
        $productFieldArr['options']['description'] = $request->description;
        foreach ($parentCats->pluck('name', 'type') as $type => $name) {
            $productField->options = Arr::add($productField->options, $type, $name);
        }
        $ProductUniqTitle->update();
        $productField->update(['options' => $productFieldArr['options']]);
        $description = $modifier->importFromMaskToShortcode($product);
        $updatedProductFields = ProductField::where('product_id', $product->id)->first();
        if ($updatedProductFields) {
            $updatedOptions = $updatedProductFields->options;
            $updatedOptions['description'] = $description;
            $updatedProductFields->update([
                'options' => $updatedOptions
            ]);
        }
        return back()->with('success', 'успешно');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $products
     * @return \Illuminate\Http\Response
     */

    public function destroy($id) {
        //$product = Product::withTrashed()->find($id);
        // if($product->trashed()){
        //     $product->forceDelete();
        // }
        $product = Product::findOrFail($id);
        $product->delete();
        return Redirect::back()->with('success', 'успех');
    }

    public function import(Request $request) {
        if (!Auth::check()) {
            return Redirect::back()->with('danger', 'вы должны авторизироватся');
        }
        $request->validate(['exel' => 'required']);
        $file = $request->file('exel');
        $filePath = $file->store('imports');
        $userId = Auth::user()->id;
        //ImportTestJob::dispatch($filePath, $userId);
        //Excel::import(new ProductAvitoImport($userId), $file);
        Excel::queueImport(new ProductAvitoImport($userId), $file)->chain([
            //     // МОЖНО СДЕЛАТЬ ЗАДАНИ В JOB НАПРИМЕР ОТПРАВКУ НА ПОЧТУ,
            //     //new AfterImportProductAvito($userId, $filePath),
        ]);
        session()->flash('success', 'файл добавлен в очередь');
        return back();
    }

    public function exportExel(Request $request) {
        $clientId = $request->client_id;
        $filename = "Client{$clientId}ForAvito.xlsx";
        return Excel::download(new ProductAvitoExportForClient($clientId), $filename);
    }
    public function exportPdf(Request $request) {
        $products = Product::with('client', 'nPrice', 'productUniqTitle', 'productField')
            ->where('client_id', $request->client_id)
            ->get();
        $rowMax = $products->max(function ($product) {
            if (!isset($product->productField->options)) {
                return -INF;
            }
            return $product->productField->options;
        });
        $rowMax = Arr::add($rowMax, 'datebegin', '');
        $rowMax = Arr::add($rowMax, 'avitostatus', '');
        return view('exports.product.product', [
            'products' => $products,
            'rowMax' => $rowMax,
        ]);
    }

    public function restore($id) {
        Product::withTrashed()->findOrFail($id)->restore();
        return Redirect::back()->with('success', 'успех');
    }
}
