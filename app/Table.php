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
        $elenco = $this->hasMany('App\Order')
            //->join("foods", "orders.food_id", "=", "foods.id")
            //->select("food_id, table_id, amount, add_percent, foods.capitolo, foods.categoria")
            //->orderBy("foods.capitolo", "ASC")
            //->orderBy("foods.categoria", "ASC")
            ->get();
        return $elenco;
    }

    public function countOrders()
    {
        return $this->hasMany('App\Order')->count();
    }

    public function totalOrders()
    {
        $total = 0;
        foreach (Table::orders() as $key => $order)
            $total += $order->amount * $order->food()->prezzo;
        return $total;
    }

    public function totalPercentAdded()
    {
        $total = 0;
        foreach (Table::orders() as $key => $order)
            $total += $order->amount * $order->food()->prezzo+ $order->amount * $order->food()->prezzo * ($order->add_percent/100);
        return $total;
    }

    public function scopeInfo($query, $input)
    {
        return $query->hasMany('App\Order')->selectRaw('table_id, food_id, COUNT(food_id) AS total')->groupBy('food_id')->get();
    }
}
