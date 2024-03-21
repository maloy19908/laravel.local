<?php

namespace App\Jobs;

use App\Events\ExportExel;
use App\Exports\ProductAvitoExportForUser;
use App\Services\ProductDescriptionModifier;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ProductExportForUserJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 120;
    public $failOnTimeout = true;

    protected $userId;
    protected $filter;
    protected $fileUrl;
    protected $fileName;
    protected $productDescriptionModifier;

    public function __construct($userId, $filter, $fileName,ProductDescriptionModifier $productDescriptionModifier){
        $this->userId = $userId;
        $this->filter = $filter;
        $this->fileName = $fileName;
        $this->productDescriptionModifier = $productDescriptionModifier;
    }

    public function handle()
    {
        if (isset($this->userId)) {
            $export = new ProductAvitoExportForUser($this->userId, $this->filter,new ProductDescriptionModifier);
            Excel::store($export, $this->fileName, 'public');
            //Event::dispatch(new ExportExel($export,$this->userId));
        }
    }
}
