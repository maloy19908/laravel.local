<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Client;
use App\Models\Nomenclature;
use App\Models\NPrice;
use App\Models\Price;
use App\Models\Product;
use App\Models\Shortcode;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use phpDocumentor\Reflection\Types\Null_;

class ClientsController extends Controller {
    public function index() {
        $userId = Auth::id();
        $clients = Client::where('user_id',$userId)->get();
        return view('clients.index', compact('clients','userId'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        // Передача $districts(областей) для выбора из списка
        return view('clients.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $userId = Auth::id();
        $request->validate([
            'phone_personal' => 'required|string|unique:clients,phone_personal',
            //'user_id' => 'required|unique:users,id',
            //'phone_avito' => 'required|string',
            //'email' => 'required|min:3|max:60|unique:clients,email',
        ]);
        $request['user_id'] = $userId;
        $client = Client::create($request->all());
        return redirect(route('clients.show', $client));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function show(Client $client, Request $request){
        $userId = Auth::id();
        $shortcodes = $client->shortcodes;
        $clients = Client::where('user_id',$userId)->get();
        $nomenclatures = Nomenclature::get();
        $shortcodes = Shortcode::where('client_id', $client->id)
        ->orWhereNull('client_id')
        ->get();
        $nPrices = NPrice::with('nomenclature')->where('client_id',$client->id)->get();
        $categories = Category::withCount(['children', 'products' => function (Builder $query) use ($client) {
            $query->where('client_id', $client->id);
        }])->get();
        $products = '';
        $category = '';
        if($request->categoryId){
            $category = $categories->find($request->categoryId);
            $products = Product::with(['ProductUniqTitle', 'nPrice', 'price' => function($query) use($request){
                $query->where('client_id', $request->client->id);
             }])->where('category_id',$request->categoryId)->where('client_id',$client->id)->withTrashed()->get()->groupBy('ProductUniqTitle.title');
        }
        if (!$client) return abort(404, 'Нет такого клиента');
        return view('clients.show', compact('client', 'clients','categories', 'products', 'shortcodes','category', 'nPrices', 'nomenclatures'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function edit(Client $client) {
        return view('clients.edit', compact('client'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function update(Client $client, Request $request) {
        // добавить области на проврку
        Validator::make($request->query(), [
            'phone_personal' => [
                'required',
                Rule::unique('phone_personal')->ignore($client->id),
            ],
        ]);
        $client->update($request->all());
        $request->session()->flash('success', 'успешно');
        return redirect(route('clients.edit', $client));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        Client::destroy($id);
        return redirect(route('clients.index'))->with('success', 'успех');
    }
}
