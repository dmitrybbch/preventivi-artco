<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Table extends Model
{
    //
    protected $fillable = [
      'nomeTavolo', 'stato'
    ];

    public function orders()
    {
        return $this->hasMany('App\Order')->selectRaw('table_id, food_id, COUNT(food_id) AS total')->groupBy('food_id')->get();
    }

    public function countOrders()
    {
        return $this->hasMany('App\Order')->count();
    }

    public function totalOrders()
    {

        $total = 0;

        foreach (Table::orders() as $key => $order) {
          $total += $order->total * $order->food()->prezzo;
        }

        return $total;
    }

    public function scopeInfo($query, $input)
    {
        return $query->hasMany('App\Order')->selectRaw('table_id, food_id, COUNT(food_id) AS total')->groupBy('food_id')->get();
    }
}
