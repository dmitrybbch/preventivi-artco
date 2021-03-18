<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Provision extends Model
{
    protected $fillable = [
        'name', 'cost' , 'unit', 'description', 'chapter', 'category', 'chapter_category', 'image', 'order'
    ];

    public function scopeSearch($query, $input)
    {
        return $query->where('name' , 'like' , "%{$input}%")->orwhere('description' , 'like' , "%{$input}%")->orwhere('category' , 'like' , "%{$input}%")->orwhere('chapter' , 'like' , "%{$input}%");
    }
}
