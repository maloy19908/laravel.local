<?php

namespace App\Http\Livewire;

use App\Models\Client;
use App\Models\Product;
use App\Models\Town;
use App\Models\User;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Products extends Component {
    use WithPagination;
    public product $product;
    public $showFilter;
    public $search = [];
    public $statusOptions = [
        'Активно',
        'Снято с публикации',
        'Истёк срок публикации',
        'Отклонено',
    ];

    public $calculation;
    public $dateStart;
    public $dateEnd;
    public $period;
    public $calc;

    public $selected = [];
    public $selectedAll = false;

    public $clients;
    public $users;
    public $changeUser;
    public $user;
    public $copiedProduct = [];
    public $status = 'Активно';
    public $productEdit = [];
    protected $paginationTheme = 'bootstrap';
    public $updateMode = false;
    // protected $queryString = ['search'];
    protected function getListeners(): array {
        return [
            'productUpdated' => 'productUpdated',
            'setFromInputMask' => 'setFromInputMask',
            'filterTowns' => 'filterTowns',
        ];
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
            ]);
        }
    }
    
    public function toggleFilter() {
        $this->showFilter = !$this->showFilter;
    }
    public function setFromInputMask($data) {
        $this->search['contactphone'] = $data['phone'];
    }

    public function productUpdated() {
        $this->render();
    }
    public function change($status) {
        $this->search['productStatus'] = $status;
    }
    public function edit($product) {
        // Если удален  Product::withTrashed()->where('id', $product)->first()
        // Тут должны быть проверки
        $this->updateMode = true;
        $this->product = Product::withTrashed()->where('id', $product)->first();
        return redirect()->to('products/' . $product . '/edit');
    }
    public function delete($id) {
        $product = Product::withTrashed()->find($id);
        $product->forceDelete();
        $this->resetPage();
    }
    public function search() {
        $this->resetPage();
    }
    public function resetFilter() {
        $this->search = [];
        $this->selected = [];
        $this->emit('resetFilter', $this->search);
    }
    public function filterTowns($towns_ids) {
        $this->search['address'] = $towns_ids;
    }
    public function filter() {
        $query = Product::query()->Where('user_id', $this->user);
        if (auth()->user()?->hasRole('admin')) {
            $query = Product::query();
        }
        $query->with('productUniqTitle', 'category', 'town', 'client', 'nPrice', 'nomenclature');

        $query->when(isset($this->search['avitoId']), function ($query) {
            $query->where('avito_id', 'like', '%' . $this->search['avitoId'] . '%');
        });
        $query->when(isset($this->search['title']), function ($query) {
            $query->whereHas('productUniqTitle', function ($query) {
                $query->where('title', 'like', '%' . $this->search['title'] . '%');
            });
        });
        $query->when(isset($this->search['contactphone']), function ($query) {
            $query->whereHas('client', function ($query) {
                $query->where('phone_personal', 'like', '%' . $this->search['contactphone'] . '%');
            });
        });
        $query->when(isset($this->search['category']), function ($query) {
            $query->whereHas('category', function ($query) {
                $query->where('name', 'like', '%' . $this->search['category'] . '%');
            });
        });
        $query->when(isset($this->search['address']), function ($query) {
            if (!empty($this->search['address'])) {
                $query->whereHas('town', function ($query) {
                    $query->whereIn('id', $this->search['address']);
                });
            }
        });
        $query->when(isset($this->search['address_street']), function ($query) {
            $query->where('address_street', 'like', '%' . $this->search['address_street'] . '%');
        });
        $query->when(isset($this->search['productStatus']), function ($query) {
            if (is_array($this->search['productStatus'])) {
                $this->search['productStatus'] = array_filter($this->search['productStatus']);
            }
            if (!empty($this->search['productStatus'])) {
                $query->whereIn('productStatus', $this->search['productStatus']);
            } else {
                $this->search['productStatus'] = '';
            }
        });
        $query->orderBy('avito_id', 'DESC')->orderBy('address_street')->orderBy('town_id');
        $this->emit('clientPhoneChanger', $this->search['contactphone'] ?? null);
        $this->emit('selectedTowns', $this->search['address'] ?? null);
        return $query->withTrashed()->paginate(50);
    }

    public function mount(Request $request) {
        $this->user = Auth::id();
        $this->copiedProduct = Session::get('copiedProduct');
    }
    public function render() {
        $this->clients = Client::where('user_id', $this->user)->get();

        $this->users = User::whereDoesntHave('roles', function ($query) {
            $query->where('slug', 'admin');
        })->get();

        if (auth()->user()?->hasRole('admin')) {
            $this->clients = Client::get();
        }
        return view('livewire.products', [
            'clients' => $this->clients,
            'users' => $this->users,
            'products' => $this->filter(),
            'towns' => Town::where('parent_id', null)->get(),
        ]);
    }
}
