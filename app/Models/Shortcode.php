<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shortcode extends Model
{
    public $timestamps = false;
    protected $guarded = [];
    use HasFactory;
    public function shortcodes() {
        return $this->belongsTo(Client::class);
    }
}
