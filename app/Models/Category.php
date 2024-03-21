<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Category extends Model
{
    use HasFactory;
        protected $fillable = [
        'name',
        'type',
        'parent_id',
    ];
    //protected $appends = ['parents'];
    public $timestamps = false;

    public function children(){
        return $this->hasMany(self::class,'parent_id');
    }
    public function childrens(){
        return $this->hasMany(self::class,'parent_id')->with('children');
    }
    public function parent(){
        return $this->belongsTo(self::class, 'parent_id');
    }
    public function products(){
        return $this->hasMany(Product::class)->withTrashed();
    }
    public function prices(){
        return $this->hasMany(Price::class);
    }
    
    public function getParentsAttribute(){
        $collection = collect([]);
        $collection->push($this);
        $parent = $this->parent;
        while ($parent) {
            $collection->push($parent);
            $parent = $parent->parent;
        }
        return $collection->reverse();
    }
}
