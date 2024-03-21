<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ClientsController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\PricesController;
use App\Http\Controllers\TownsController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\ShortcodesController;
use App\Http\Controllers\UsersController;
use App\Http\Livewire\Nomenclature\NomenclatureList;
use App\Http\Livewire\Prices\PriceCreate;
use App\Http\Livewire\Prices\PriceDashbord;
use App\Http\Livewire\Prices\PriceEdit;
use App\Http\Livewire\Prices\PriceIndex;
use App\Http\Livewire\Product\ProductExportForUser;
use App\Http\Livewire\Product\ProductIndex;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
/* Route::get('/{any}',function(){
    return view('index');
})->where('any', '.*'); */
//Auth::routes();
Auth::routes(['register' => true]);
Route::get('download/{filename}', [FileController::class, 'download']);
Route::group(['middleware' => 'auth'], function(){
    Route::resource('users', UsersController::class);
    Route::resource('clients', ClientsController::class);
    Route::post('users/switch{userId}',[UsersController::class, 'switchUser'])->name('switch.user');
    Route::get('/', [ClientsController::class, 'index'])->name('home');
        Route::get('clients/{client}/category/{categoryId}', [ClientsController::class, 'show'])->name('Client.category');
        Route::resource('towns', TownsController::class);
        Route::resource('shortcodes', ShortcodesController::class);
        Route::resource('products', ProductsController::class);
    
        Route::post('products.createNpriceLink',[ProductsController::class, 'createNpriceLink'])->name('products.createNpriceLink');
        Route::post('prices.createLink',[PricesController::class, 'createLink'])->name('prices.createLink');
        Route::post('prices.deleteLink',[PricesController::class, 'deleteLink'])->name('prices.deleteLink');
    
    
        Route::resource('category',CategoryController::class);
        //Импорт
        Route::post('towns.import', [TownsController::class, 'import'])->name('towns.import');
        Route::post('products.import', [ProductsController::class, 'import'])->name('products.import');
        //Экспорт
        Route::get('products.export.exel', [ProductsController::class, 'exportExel'])->name('products.export.exel');
        Route::get('products.export.pdf', [ProductsController::class, 'exportPdf'])->name('products.export.pdf');
        Route::get('priceListClient.export', [PricesController::class, 'priceListClientExport'])->name('priceListClient.export');
    // Route::put('products/{id}/arhive', [ProductsController::class, 'arhive'])->name('products.arhive');
    // Route::post('products/{id}/dateBegin', [ProductsController::class, 'dateBegin'])->name('products.dateBegin');
    // Route::get('products/{id}/restore', [ProductsController::class, 'restore'])->name('products.restore');
    // liveware
        Route::get('nomenclatures', NomenclatureList::class)->name('nomenclatures');
        
        Route::get('product.index', ProductIndex::class)->name('product.index');
        Route::get('vue',function(){
            return view('vue/vue-template');
        })->name('vue');
    
    

    // Route::get('cash',function(){
    //     Artisan::call('cache:clear');
    //     Artisan::call('route:clear');
    //     Artisan::call('view:clear');
    //     return redirect('productsAvito');
    // });
    Route::fallback(function(){
        return abort('404','Ничего не найдено');
    });
});
// очистка базы 
// Route::get('fresh',function(){
//     $fresh = Artisan::call('migrate:fresh --seed');
//     return redirect('productsAvito');
// });