<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Table extends Model
{
    //
    protected $fillable = [
      'nomeTavolo', 'stato', 'cliente', 'noteAggiuntive', 'creatoDa', 'ricarico', 'creatoInData',
    ];

    public function orders()
    {
        $elenco = $this->hasMany('App\Order')->selectRaw('table_id, food_id, COUNT(food_id) AS total')->groupBy('food_id')->get();

        return $elenco;
    }

    public function countOrders()
    {
        return $this->hasMany('App\Order')->count();
    }

    public function totalOrders()
    {

        $total = 0;

        foreach (Table::orders() as $key => $order) {
            //error_log("DEBUG orders(): key=" . $key . " - order=". $order);
            $total += $order->total * $order->food()->prezzo;
        }

        return $total;
    }

    public function scopeInfo($query, $input)
    {
        return $query->hasMany('App\Order')->selectRaw('table_id, food_id, COUNT(food_id) AS total')->groupBy('food_id')->get();
    }
}
