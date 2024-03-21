<?php

namespace App\Http\Livewire\Product;

use App\Exports\FilterProductExport;
use App\Models\Category;
use App\Models\Client;
use App\Models\Product;
use App\Models\ProductField;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class ProductIndex extends Component {
    use WithPagination;
    public $query;
    public $filtered;
    public $showStatus;
    public $showForm = [];
    public $dateBegin = [];

    public $selected = [];
    public $selectedAll = false;

    public $status = 'Активно';
    public $statusOptions = [
        'Активно',
        'Снято с публикации',
        'Истёк срок публикации',
        'Отклонено',
        'Заблокировано',
        'В архиве',
    ];

    public $user;

    public $townsID;

    protected $paginationTheme = 'bootstrap';

    protected function getListeners(): array {
        return [
            'removeDateBegin' => 'removeDateBegin',
            'selectedTowns' => 'selectedTowns',
        ];
    }

    protected function mostFrequentTown($townID = 'town_id') {
        $phone = $this->query['phone'];
        $client = Client::with('products')->where('phone_personal', $phone)->first('id');
        $towns = $client->products->pluck($townID);
        $counts = $towns->groupBy(function ($town) {
            return $town;
        })->map(function ($group) {
            return $group->count();
        })->sortByDesc(function ($count) {
            return $count;
        });
        return $counts->keys()->first();
    }

    public function updatedSelected($value, $id) {
        if (!$this->selected[$id]) {
            unset($this->selected[$id]);
        } else {
            $this->selected[$id] = $id;
        }
    }

    public function updatedSelectedAll() {
        foreach ($this->filter()->pluck('id')->all() as $key => $id) {
            if (isset($this->selected[$id])) {
                unset($this->selected[$id]);
            } else {
                $this->selected[$id] = $id;
            }
        }
    }
    public function changeSelected() {
        $products = Product::whereIn('id', $this->selected)
            ->get();
        foreach ($products as $product) {
            $product->update([
                'productStatus' => $this->status,
                'dateBegin' => '',
            ]);
        }
    }
    public function createDateBegin($id) {
        $product = Product::withTrashed()->find($id);
        $this->validate([
            'dateBegin.*' => 'date|date_format:Y-m-d',
        ]);
        //$product->restore();
        $product->dateBegin = $this->dateBegin[$id] ?? '';
        $product->productStatus = 'Активно';
        $product->save();
        $this->showForm[$id] = false;
    }
    public function confirmAction($id) {
        $this->emit('confirmAction', $id);
    }
    public function removeDateBegin($id) {
        $product = Product::find($id);
        $product->dateBegin = '';
        $product->productStatus = 'Снято с публикации';
        $product->save();
        $product->refresh();
    }
    public function copy($id = null) {
        if (!$id) {
            $copiedProducts = Session::get('copiedProducts', []);
            Session::put('copiedProducts', $this->selected);
        } else {
            $copiedProducts = Session::get('copiedProducts', []);
            if (in_array($id, $copiedProducts)) {
                $copiedProducts = array_diff($copiedProducts, [$id]);
            } else {
                $copiedProducts[] = $id;
            }
            Session::put('copiedProducts', $copiedProducts);
        }
    }
    public function selectedTowns($townsID) {
        $this->townsID = $townsID;
    }
    public function paste() {
        if (isset($this->query['phone'])) {
            $phone = $this->query['phone'];
            $client = Client::with('products')->where('phone_personal', $phone)->first(['id','user_id']);
            if ($client) {
                $copiedProducts = collect(Session::get('copiedProducts', []));
                // Город который чаще всего 
                $mostFrequentTown = $this->mostFrequentTown();
                if (empty($mostFrequentTown)) {
                    $address_street = $this->mostFrequentTown('address_street');
                }
                // Сохраняем все копии
                
                // Получаем ID из компонента выбора городов
                $townsID = ($this->townsID && is_array($this->townsID)) ? $this->townsID : [$mostFrequentTown];
                foreach($copiedProducts as $id){
                    $product = Product::withTrashed()
                    ->with('client', 'category', 'nPrice', 'nomenclature', 'town', 'productUniqTitle', 'productField')
                    ->findOrFail($id);
                    foreach ($townsID as $townID) {
                        $copy = $product->replicate();
                        $copyName = "COPY-" . uniqid();
                        $copy->avito_id = '';
                        $copy->my_id = $copyName;
                        $copy->client_id = $client ? $client->id : null;
                        if(!empty($townID)){
                          $copy->town_id = $townID;
                        }else{
                          $copy->town_id = null;
                          $copy->address_street = $address_street;
                        }
                        $copy->dateBegin = null;
                        $copy->productStatus = 'Удаленно';
                        $copy->deleted_at = now();
                        $copy->user_id = $client->user_id;
                        $copy->save();
            
                        $copyProductField = new ProductField([
                          'product_id' => $copy->id,
                          'options' => $product->productField->options,
                        ]);
                        $copyProductField->save();
                      }
                }
            }
        }
    }
    public function clearSession() {
        session()->forget('copiedProducts');
    }
    public function toggleStatus() {
        $this->showStatus =  !$this->showStatus;
    }
    public function toggleListing($id) {
        $product = Product::find($id);
        if (isset($product->listingFee) && $product->listingFee == 'Package+Single') {
            $product->listingFee = 'Package';
            $product->save();
        } else {
            $product->listingFee = 'Package+Single';
            $product->save();
        }
    }
    public function edit($id) {
        // Если удален  Product::withTrashed()->where('id', $product)->first()
        // Тут должны быть проверки
        return redirect()->to('products/' . $id . '/edit');
    }
    public function export() {
        $this->filtered = $this->filter()->items();
        dd($this->filtered);
        $file = Excel::download(new FilterProductExport($this->filtered), 'filter.xlsx');
        return $file;
    }
    public function search() {
        $this->resetPage();
    }
    public function filter() {
        $query = DB::table('products')
            ->join('productuniqtitle', 'products.product_uniq_title_id','productuniqtitle.id')
            ->join('clients', 'products.client_id','clients.id')
            ->join('categories', 'products.category_id', 'categories.id')
            ->leftJoin('n_prices', function ($join) {
                $join->on('products.nomenclature_id','n_prices.nomenclature_id')
                    ->on('products.client_id','n_prices.client_id');
            })
            ->select('products.*', 'productuniqtitle.title AS title', 'clients.phone_personal AS phone_personal', 'categories.name AS category_name', 'n_prices.cost AS cost', 'products.deleted_at as trashed', 'address_street as address')
            ->when($this->user, fn ($q) => $q->where('products.user_id', $this->user))
            ->when(isset($this->query['avitoId']), fn ($q) => $q->where('products.avito_id', 'LIKE', "%" . $this->query['avitoId'] . "%"))
            ->when(isset($this->query['title']), fn ($q) => $q->where('productuniqtitle.title', 'LIKE', "%" . $this->query['title'] . "%"))
            ->when(isset($this->query['address']), fn ($q) => $q->where('address_street', 'LIKE', "%" . $this->query['address'] . "%"))
            ->when(isset($this->query['category']), fn ($q) => $q->where('categories.name', 'LIKE', "%" . $this->query['category'] . "%"))
            ->when(isset($this->query['phone']), fn ($q) => $q->where('clients.phone_personal', 'LIKE', "%" . $this->query['phone'] . "%"))
            ->when(isset($this->query['productStatus']), function ($q) {
                $statuses = array_filter((array)$this->query['productStatus']);
                return $q->whereIn('productStatus', $statuses);
            });
    
        return $query->paginate(50, ['address', 'title', 'phone_personal', 'category_name']);
    }
    public function clearQuery() {
        $this->reset('query', 'selected');
    }
    public function render() {
        $this->user = auth()->user()?->id;
        $copiedProducts = Session::get('copiedProducts', []);
        $clients = DB::table('clients')->where('user_id', $this->user)->get(['phone_personal', 'id', 'name']);
        return view('livewire.product.product-index', [
            'clients' => $clients,
            'products' => $this->filter(),
            'copiedProducts' => $copiedProducts,
        ]);
    }
}
