<?php

namespace App\Http\Controllers;

use App\Imports\TownsImport;
use App\Jobs\ImportTownsJob;
use App\Models\Client;
use App\Models\District;
use App\Models\Town;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class TownsController extends Controller
{
    public function index(Client $client,Request $request){
        $towns = Town::withCount('childrens')->where('parent_id',null)->get();
        return view('towns.index', compact('towns','client'));
    }
    public function create(){
        return view('towns.create', [
            'towns' => Town::get(),
        ]);
    }
    public function store(Request $request){
        $request->validate([
            'name' => 'required|string|unique:towns,name,',
            'parent_id' => 'required|integer',
        ]);
        Town::updateOrCreate([
            'name'=>$request['name'],
            'parent_id'=>$request['parent_id'],
        ]);
        return Redirect::route('towns.index')->with('success', 'успех');
    }
    public function show(Town $town, Client $client,Request $request){
        $towns = Town::withCount('childrens')->where('parent_id', $request->parent_id)->get();
        return view('towns.index', compact('towns', 'client'));
    }
    public function edit(Town $town){
        return view('towns.edit',[
            'town' => $town,
            'towns' => Town::get(),
        ]);
    }
    public function update(Town $town, Request $request){
        dd($request->all());
        $request->validate([
            'name' => 'required|string',
        ]);
        $town->update($request->all());
        return Redirect::route('towns.edit',[
            'town'=>$town,
        ])->with('success', 'успех');
    }
    public function destroy(Client $client, Request $request){
        Town::destroy('id',$request->town);
        return Redirect::route('towns.index',$client)->with('success','успех');
    }
    public function import(Request $request) {
        $request->validate(['xlsx' => 'required|file']);
        if ($request->hasFile('xlsx')) {
            $file = $request->file('xlsx');
            $filePath = $file->store('imports');
            //ImportTownsJob::dispatch($filePath);
            //Excel::import(new TownsImport, $file);
            Excel::queueImport(new TownsImport, $file);
        }
        return redirect()->back()->with('success', 'Загрузка завершена');
    }

}
