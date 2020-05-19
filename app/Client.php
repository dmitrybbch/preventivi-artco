<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    //
    protected $fillable = [
        'nome', 'email', 'telefono', 'indirizzo', 'capCittaProv',
    ];

    public function scopeSearch($query, $input)
    {
        return $query->where('nome' , 'like' , "%{$input}%")->orwhere('email' , 'like' , "%{$input}%")->orwhere('capCittaProv' , 'like' , "%{$input}%")->orwhere('indirizzo' , 'like' , "%{$input}%");
    }
}
