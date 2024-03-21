<?php

namespace Database\Seeders;

use App\Imports\TownsImport;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Maatwebsite\Excel\Facades\Excel;

class TownSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(){
        $file = storage_path('app/towns.xlsx');
        if(isset($file)){
            Excel::import(new TownsImport, $file);
        }
    }
}
