<?php

use App\Http\Controllers\AuthApiController;
use App\Http\Controllers\ProductsAvitoController;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login/', [AuthApiController::class, 'login']);
Route::post('/register/', [AuthApiController::class, 'register']);
Route::get('/products',function(){
    $products = DB::table('products')
    ->join('productuniqtitle', 'products.product_uniq_title_id', '=', 'productuniqtitle.id')
    ->join('clients', 'products.client_id', '=', 'clients.id')
    ->join('categories', 'products.category_id', '=', 'categories.id')
    ->select('products.*', 'productuniqtitle.title AS title', 'clients.phone_personal AS phone_personal', 'categories.name AS category_name');
    $results = $products->paginate(50, ['avito_id', 'my_id', 'address_street', 'productStatus', 'listingFee', 'title', 'phone_personal', 'category_name']);
    return response()->json($results);
});





