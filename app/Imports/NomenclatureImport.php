<?php

namespace App\Imports;

use App\Models\Category;
use App\Models\Nomenclature;
use App\Models\NPrice;
use App\Models\Price;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class NomenclatureImport implements ToModel, WithHeadingRow, WithMultipleSheets {

    public function model(array $rows) {
        $v = Validator::make($rows, [
            'name' => 'required',
            'category' => 'required',
        ],);
        $v->validate();
        Nomenclature::updateOrCreate([
          'name' => $rows['name'],
          'category' => mb_strtolower($rows['category']),
      ]);
    }
    public function sheets(): array {

        return [
            0 => new NomenclatureImport(),
        ];
    }
}
