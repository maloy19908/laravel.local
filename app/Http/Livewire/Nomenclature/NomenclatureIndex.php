<?php

namespace App\Http\Livewire\Nomenclature;

use App\Exports\PriceListClientExport;
use App\Imports\NPriceListImport;
use App\Models\Client;
use App\Models\Nomenclature;
use App\Models\NPrice;
use Livewire\Component;
use Illuminate\Validation\Validator;
use Illuminate\Validation\Rule;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;

class NomenclatureIndex extends Component {
  use WithFileUploads;
  public $nomenclatureName;
  public $nomenclatureCategory;
  public $clientId;
  public $show;
  public $file;
  protected $listeners = ['nomenclatureDeleted' => '$refresh'];
  public function toogleForm() {
    $this->show = !$this->show;
  }

  public function addNPriceList(){
    $this->validate([
      'file' => 'required|file|mimes:xls,xlsx|max:10240', // Максимальный размер файла 10MB
    ]);
    $path = $this->file->getRealPath();
    Excel::import(new NPriceListImport($this->clientId), $path);
    $this->emit('fileUploaded');
  }
  
  public function downloadPriceList() {
    $fileName = "PriceList" . $this->clientId . ".xlsx";
    return Excel::download(new PriceListClientExport($this->clientId),$fileName);
  }
  public function saveNomenclature() {
    $this->validate([
      'nomenclatureName' => [
        'required',
        Rule::unique('nomenclatures', 'name')->where(function ($query) {
          return $query->where('client_id', $this->clientId);
        })->ignore($this->clientId)
      ],
      'nomenclatureCategory'=>'required'
    ]);
    $nomenclature = new Nomenclature([
      'name'=> $this->nomenclatureName,
      'category'=> mb_strtolower($this->nomenclatureCategory),
      'client_id'=>$this->clientId,
    ]);
    $nomenclature->save();
    $this->show = false;
    $this->emit('nomenclatureCreate');
    $this->reset('nomenclatureName','nomenclatureCategory');
  }


  public function deleteNomenclature() {
    $this->nomenclature->delete();
    $this->emit('nomenclatureDeleted');
  }

  public function render() {
    $groops = Nomenclature::where('client_id', $this->clientId)->orWhere('client_id',null)->orderBy('name')->get()->groupBy(function ($item, $key) {
      return strpos($item->name, 'в мешках') !== false ? 'в мешках' : 'без мешков';
    });
    return view('livewire.nomenclature.nomenclature-index',[
      'groops'=> $groops,
    ]);
  }
}