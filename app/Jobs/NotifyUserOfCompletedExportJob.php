<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;

class NotifyUserOfCompletedExportJob implements ShouldQueue
{
    use Queueable, SerializesModels;
    public $userId;
    public $filePath;
    public $user;
    
    public function __construct(User $user){
        $this->user = $user;
    }

    // НУжно сделать отправку на почту
    public function handle(){
        //$this->user->notify(new ExportReady());
    }
}
