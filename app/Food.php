<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Food extends Model
{

    protected $fillable = [
        'nome', 'prezzo' , 'unita', 'descrizione', 'capitolo', 'categoria', 'capitolo_categoria', 'immagine',
    ];

    public function scopeSearch($query, $input)
    {
        return $query->where('nome' , 'like' , "%{$input}%")->orwhere('descrizione' , 'like' , "%{$input}%")->orwhere('categoria' , 'like' , "%{$input}%")->orwhere('capitolo' , 'like' , "%{$input}%");
    }
}
