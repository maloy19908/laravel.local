<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;


class Town extends Model
{
    protected $fillable = [
        'name',
        'parent_id',
    ];
    public $timestamps = false;
    use HasFactory;
    use sluggable;
    // livewire ломает
    //protected $appends = ['parents'];
    
    public function sluggable(): array {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    public function children() {
        return $this->hasMany(self::class, 'parent_id');
    }
    public function childrens() {
        return $this->hasMany(self::class, 'parent_id')->with('children');
    }
    public function parent() {
        return $this->belongsTo(self::class, 'parent_id');
    }
    public function getParentsAttribute() {
        $collection = collect([]);
        $collection->push($this);
        $parent = $this->parent;
        while ($parent) {
            $collection->push($parent);
            $parent = $parent->parent;
        }
        return $collection->reverse();
    }
    public function client() {
        return $this->belongsTo(Client::class);
    }
    public function products() {
        return $this->hasMany(Product::class);
    }

    public function deleteChildren() {
        foreach ($this->children as $child) {
            $child->deleteChildren(); // Рекурсивный вызов для дочерних элементов
            $child->delete(); // Удаление самого дочернего элемента
        }
    }
    public function delete() {
        $this->deleteChildren(); // Удаление всех дочерних элементов
        return parent::delete();
    }
}
