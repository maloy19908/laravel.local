<?php

namespace App\Http\Controllers;

use App\Exports\PriceListClientExport;
use App\Imports\PriceListImport;
use App\Models\Category;
use App\Models\Client;
use App\Models\Price;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class PricesController extends Controller {
    public function index(Request $request) {
        $client_id = $request->client_id;
        $category_id = $request->category_id;
        $prices = Price::where('client_id',$client_id)
        ->Orwhere('client_id',null)
        ->get()
        ->sortBy([
            ['client_id', 'desc'],
        ]);
        $groops = $prices->groupBy('category.name');
        return view('prices.index', compact('prices', 'groops','client_id'));
    }
    public function create(Request $request) {
        $categories = Category::get();
        // НУЖНО РЕАЛИЗОВАТЬ ВЫБОР МЕТР КУБИЧ
        $priceType = [];
        return view('prices.create', compact('categories', 'priceType'));
    }
    public function store(Request $request, Price $prices) {
        $client_id = $request->client_id;
        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                'string',
                Rule::unique('prices')->where(function ($query) use ($client_id) {
                    return $query->where('client_id', $client_id);
                }),
            ],
            'cost' => 'required|integer',
            'priceType' => 'required',
            'client_id' => 'integer|exists:clients,id',
            'category_id' => 'required|integer|exists:categories,id',
        ]);
        $prices->create($validator->validated());
        return  redirect(route('prices.index', [
            'client_id' => $request->client_id,
            'category_id' => $request->category_id,
        ]))->with('success', 'успех');
    }
    public function show(Request $request) {
        __METHOD__;
    }
    public function edit(Price $price, Request $request) {
        $categories = Category::get();
        // НУЖНО РЕАЛИЗОВАТЬ ВЫБОР МЕТР КУБИЧ
        $priceType = [];
        return view('prices.edit', compact('price', 'categories', 'priceType'));
    }
    public function update(Price $price, Request $request) {
        $request->validate([
            'name' => 'required|string',
            'cost' => 'required|integer',
            'client_id' => 'integer|exists:clients,id',
            'category_id' => 'required|integer|exists:categories,id',
        ]);
        $price->update($request->all());
        return  redirect(route('prices.index', [
            'client_id' => $request->client_id,
            'category_id' => $request->category_id,
        ]))->with('success', 'успех');
    }

    public function destroy(Price $price, Request $request) {
        $price->delete();
        return  back();
    }

    public function destroyLink(Request $request) {
        $Products = Product::withTrashed()->where('client_id', $request->client_id)
            ->where('id', $request->product_id)
            ->first();
        $Products->prices()->detach();
        return  redirect(route('clients.show', [
            'client' => $request->client_id,
            'categoryId' => $request->category_id,
        ]))->with('success', 'успех');
    }

    public function createLink(Request $request) {


        // if(isset($request->product_id)){

        //     $products = Product::whereIn('id', $request->product_ids)->get();
        //     foreach($products as $product){
        //         if($request->price_id==0){
        //             $product->prices()->detach();
        //         }
        //         else{
        //             $product->prices()->sync($request->price_id);
        //         }
        //     }
        //     return back()->with('success', 'успех');
        // }
        $request->validate([
            'client_id' => 'required|integer|exists:clients,id',
            'price_id' => 'required|integer',
            'category_id' => 'required|integer|exists:categories,id',
            'product_id' => 'required|array',
            'product_id.*' => 'required|integer|exists:products,id',
        ]);
        $products = Product::withTrashed()->where('client_id', $request->client_id)
            ->whereIn('id', $request->product_id)
            ->get();
        foreach ($products as $product) {
            if ($request->price_id == -1) {
                $product->update(['price_id' => null]);
            } else {
                $product->update(['price_id'=>$request->price_id]);
            }
        }
        return redirect(route('clients.show', [
            'client' => $request->client_id,
            'categoryId' => $request->category_id,
        ]))->with('success', 'успех');
    }
    
    public function priceListClientExport(){
        $client_id = request()->input('client_id');
        if($client_id){
            $client = Client::find($client_id);
            $name = $client->name . "-" . $client->phone_personal;
        }else{
            $name = '';
        }
        return Excel::download(new PriceListClientExport($client_id), $name.'priceListClientExport.xlsx');
    }
    public function import(Request $request) {
        $request->validate(['exel' => 'required|file|max:3072']);
        $file = $request->file('exel');
        Excel::import(new PriceListImport, $file);
        return redirect()->back()->with('success', 'Загрузка завершена');
    }
}
