<?php

namespace App\Http\Livewire\Nomenclature;

use App\Imports\NomenclatureImport;
use App\Models\Client;
use App\Models\Nomenclature;
use App\Services\ShortcodeFromNomenclature;
use Doctrine\DBAL\Schema\Index;
use Livewire\Component;
use Illuminate\Http\Request;
use Illuminate\Validation\Validator;
use Illuminate\Validation\Rule;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;

class NomenclatureList extends Component {
  use WithFileUploads;
  public $name;
  public $category;
  public $file;
  public $nomenclature;
  public $showForm;
  public $editId;
  public $update;
  public $arr = [];

  protected $rules = [
    'nomenclature.name' => 'required',
    'nomenclature.category' => 'required',
  ];

  public function addNPriceList(){
    $this->validate([
      'file' => 'required|file|mimes:xls,xlsx|max:10240', // Максимальный размер файла 10MB
    ]);
    $path = $this->file->getRealPath();
    Excel::import(new NomenclatureImport(), $path);
    $this->emit('fileUploaded');
  }
  public function add() {
    $this->showForm = true;
  }
  public function save() {
    $this->validate([
      'nomenclature.name' => [
        'required',
        Rule::unique('nomenclatures', 'name')->where(function ($query) {
          return $query->where('client_id', null);
        })
      ],
      'nomenclature.category' => 'required',
    ]);

    $nomenclature = new Nomenclature([
      'name' => $this->nomenclature['name'],
      'category' => trim(mb_strtolower($this->nomenclature['category'])),
    ]);

    $nomenclature->save();
    if($nomenclature->id){
      ShortcodeFromNomenclature::getInstance()->createShortcode($nomenclature->name);
    }

    $this->nomenclature = '';
    $this->showForm = false;
  }

  public function update($id) {
    if (!$this->update) {
      return;
    }
    $this->validate([
      'name' => [
        'required',
        Rule::unique('nomenclatures', 'name')->ignore($id)->where(function ($query) {
          return $query->where('client_id', null);
        })
      ],
      'category' => 'required',
    ]);
    $this->nomenclature = Nomenclature::find($id);
    $shortcodeName = $this->nomenclature->name;
    $this->nomenclature->update([
      'name' => $this->name,
      'category' => $this->category,
    ]);
    ShortcodeFromNomenclature::getInstance()->editShortcode($shortcodeName,$this->name);
    $this->editId = '';
    $this->nomenclature = '';
    session()->flash('success', 'обновленно');
  }
  public function edit($id) {

    $this->update = true;
    $nomenclature = Nomenclature::find($id);
    if(isset($nomenclature)){
      $this->editId = $id;
      $this->name = $nomenclature->name;
      $this->category = $nomenclature->category;
    }
  }
  public function close() {
    $this->update = false;
    $this->editId = '';
  }
  public function remove($id) {
    $nomenclature = Nomenclature::find($id);
    if (isset($nomenclature)) {
      $nomenclature->delete();
      ShortcodeFromNomenclature::getInstance()->deleteShortcode($nomenclature->name);
    }
  }
  public function render() {

    $groops = Nomenclature::where('client_id', null)->orderBy('name')->get()->groupBy(function ($item, $key) {
      return $item->category;
    });
    return view('livewire.nomenclature.nomenclature-list', [
      'groops' => $groops,
    ]);
  }
}
