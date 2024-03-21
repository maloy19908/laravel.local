<?php

namespace App\Jobs;

use App\Imports\ProductAvitoImport;
use App\Imports\TownsImport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Maatwebsite\Excel\Facades\Excel;

class ImportProductAvitoJob implements ShouldQueue{
    protected $filePath;
    protected $userId;
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($filePath, $userId){
        $this->filePath = $filePath;
        $this->userId = $userId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(){
        try {
            Excel::import(new ProductAvitoImport, $this->filePath, null, \Maatwebsite\Excel\Excel::XLSX);
        } catch (\Exception $e) {
            dd('Import error: ' . $e->getMessage());
        }
    }
}
