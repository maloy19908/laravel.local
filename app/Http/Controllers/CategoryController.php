<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class CategoryController extends Controller
{
    public function index(Request $request){
        return view('category.index',[
            'categories'=> Category::with('children')->where('parent_id',0)->get(),
        ]);
    }

    public function show(Category $category){
        dd($category->load('childrens')->toArray());
        return view('category.show',[
            'category'=>$category->load('childrens'),
        ]);
    }
    public function create() {
        return view('category.create',[
            'category'=>[],
            'categories'=>Category::with('children')->where('parent_id',0)->get(),
        ]);
    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'required|unique:categories,name,',
            'parent_id' => 'integer',
        ]);
        Category::updateOrCreate(
            [
                'name'=>$request->name,
                'parent_id'=>$request->parent_id,
            ]
        );
        return redirect()->route('category.index')->with('success', 'успешно');
    }

    public function edit(Category $category){
        return view('category.edit',[
            'category'=>$category,
            'categories' => Category::with('children')->where('parent_id', 0)->get(),
        ]);
    }

    public function update(Category $category,Request $request) {
        $request->validate([
            'name' => 'required|string',
        ]);
        $category->update($request->all());
        return back()->with('success', 'успешно');
    }

    public function destroy(Category $category) {
        $category->delete();
        return back()->with('success', 'успешно');
    }
}