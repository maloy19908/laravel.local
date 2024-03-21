<?php

namespace App\Http\Livewire\Towns;

use App\Models\Product;
use App\Models\Town;
use Illuminate\Support\Arr;
use Livewire\Component;

class TownsIndex extends Component {
    public $parentTowns = [];
    public $selectedCities = [];
    public $isActive;

    protected function getListeners(): array {
        return [
            'resetFilter' => 'resetFilter',
        ];
    }
    protected function removeChildren($cities) {
        foreach ($cities as $key => $city) {
            if (is_array($city)) {
                $cities[$key] = $this->removeChildren($city);
                if (empty($cities[$key])) {
                    unset($cities[$key]);
                }
            } elseif ($city === false) {
                unset($cities[$key]);
            }
        }
        return $cities;
    }
    public function resetFilter($towns_ids) {
        $this->parentTowns = $towns_ids;
        if(empty($this->parentTowns)){
            $this->isActive = false;
        }
    }
    
    protected function flattenArray($cities, &$result = []) {
        foreach ($cities as $key => $city) {
            if (is_array($city)) {
                $this->flattenArray($city, $result);
            }else{
                $result[$key] = $key;
            }
        }
        return array_keys($result);
    }

    public function updatedParentTowns() {
        $this->parentTowns = $this->removeChildren($this->parentTowns);
        $this->selectedCities =  $this->flattenArray($this->parentTowns);
        if (!empty($this->parentTowns)) {
            $this->isActive = true;
        }
        $this->emit('filterTowns', $this->selectedCities);
        $this->emit('selectedTowns', $this->selectedCities);
    }

    public function render() {
        $towns = Town::where('parent_id', null)->get();
        return view('livewire.towns.towns-index', [
            'towns' => $towns,
        ]);
    }
}
