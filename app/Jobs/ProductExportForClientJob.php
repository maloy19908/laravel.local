<?php

namespace App\Jobs;

use App\Exports\ProductAvitoExportForClient;
use App\Services\ProductDescriptionModifier;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ProductExportForClientJob implements ShouldQueue,ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $clientId;
    protected $filter;

    public function __construct($clientId, $filter){
        $this->clientId = $clientId;
        $this->filter = $filter;
    }

    public function handle(){
        if (isset($this->clientId)) {
            $filename = "Client{$this->clientId}ForAvito.xlsx";
            $file = Excel::store(new ProductAvitoExportForClient($this->clientId, $this->filter,new ProductDescriptionModifier), $filename, 'public');
        }
    }
}