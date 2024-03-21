<?php

namespace App\Imports;

use App\Models\Town;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TownsImport implements ToCollection, WithHeadingRow , WithBatchInserts, WithChunkReading, ShouldQueue {
    
    public $town;
    public function collection(Collection $rows) {
        set_time_limit(10 * 60);
        // СДЕЛАТЬ ПРОВЕРКИ !!!!!!!
        foreach ($rows as $row) {
            
            $parentTowns = Town::updateOrCreate(['name' => $row['name']],['parent_id' => null,]);
            $parentTowns->coord = $row['coord'];
            $parentTowns->save();
            $childTown = $parentTowns;
            for ($i = 3; $i <= 14; $i += 1) {

                if (isset($parentTowns) && isset($row["name$i"])){
                    $childTown = Town::updateOrCreate(['name' => $row["name$i"], 'parent_id' => $childTown->id]);
                }
                $parentTowns = $childTown;
                if(isset($row["coord6"])){
                    $parentTowns->coord  = $row["coord6"];
                    $parentTowns->save();
                }else{
                    $parentTowns->coord  = $row["coord"];
                    $parentTowns->save();
                }
            }
            // $name = Town::updateOrCreate([
            //     'name' => $row['name']
            // ]);
            // $name->coord = $row['coord'];
            // $name->save();
            // if (!empty($row['name3'])) {
            //     $name3 = Town::updateOrCreate([
            //         'name' => $row['name3']]);
            //     $name3->coord = $row['coord'];
            //     $name3->parent_id = $name->id;
            //     $name3->save();

            // }
            // if (!empty($row['name5'])) {
            //     $name5 = Town::updateOrCreate([
            //         'name' => $row['name5']]);
            //     $name5->parent_id = $name->id;
            //     $name5->coord = $row['coord'];
            //     $name5->save();
            // }
            // if (!empty($row['name8'])) {
            //     $name8 = Town::updateOrCreate([
            //         'name' => $row['name8']
            //     ]);
            //     $name8->coord = $row['coord6'];
            //     $name8->parent_id = $name->id;
            //     $name8->save();
            // }
            // if (!empty($row['name10'])) {
            //     $name10 = Town::updateOrCreate([
            //         'name' => $row['name10']]);
            //     $name10->coord = $row['coord6'];
            //     $name10->parent_id = $name8->id;
            //     $name10->save();

            // }
            // if (!empty($row['name8']) && !empty($row['name12'])) {
            //     $name12 = Town::updateOrCreate([
            //         'name' => $row['name12']]);
            //     $name12->coord = $row['coord6'];
            //     $name12->parent_id = $name8->id;
            //     $name12->save();
            // }
            // if (!empty($row['name8']) && !empty($row['name14'])) {
            //     $name14 = Town::updateOrCreate([
            //         'name' => $row['name14']]);
            //     $name14->coord = $row['coord6'];
            //     $name14->parent_id = $name8->id;
            //     $name14->save();
            // }
        }

        return;
    }
    public function rules(): array {

        return [
            'coord' => 'required|string|max:255',
            'coord6' => 'required_if:name8,!=,null|string|max:255',
            'name' => 'required|string|max:255',
            'name8' => 'required|string|max:255',
            'name10' => 'required_if:name8,!=,null|string|max:255',
            'name12' => 'required_if:name8,!=,null|string|max:255',
        ];
    }
    public function sheets(): array {

        return [
            0 => new TownsImport(),
        ];
    }
    public function startRow(): int {
        return 1;
    }

    public function batchSize(): int {
        return 200;
    }

    public function chunkSize(): int {
        return 200;
    }
}
