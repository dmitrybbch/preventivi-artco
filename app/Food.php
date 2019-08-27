<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Food extends Model
{
    protected $fillable = [
        'nome', 'prezzo' , 'descrizione',
    ];

    public function scopeSearch($query, $input)
    {
        return $query->where('nome' , 'like' , "%{$input}%")->orwhere('descrizione' , 'like' , "%{$input}%");
    }
}
