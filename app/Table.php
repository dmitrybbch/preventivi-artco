<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Table extends Model
{
    // Stati: libero, occupato, servito
    protected $fillable = [
      'nomeTavolo', 'stato', 'client_id', 'noteAggiuntive', 'creatoDa', 'ricarico', 'creatoInData',
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

    public function totalPercentAdded() //TODO: si potrebbe mettere togliere questa funzione ed aggiungere un parametro a quella originale
    {
        $total = 0;
        foreach (Table::orders() as $key => $order){
            $total += $order->amount * $order->food()->prezzo;
            $total += $order->amount * $order->food()->prezzo * ($order->add_percent/100);
        }
            
        return $total;
    }

    public function totalPercentAddedChapter($chapter) //TODO: si potrebbe mettere togliere questa funzione ed aggiungere un parametro a quella originale
    {
        $total = 0;
        foreach (Table::orders() as $key => $order){
            if( strcmp($order->food()->capitolo, $chapter) == 0){
                $total += $order->amount * $order->food()->prezzo;
                $total += $order->amount * $order->food()->prezzo * ($order->add_percent/100);
            }
        
        }
            
        return $total;
    }

    public function currentClientDB()
    {
        //logger("Nais ".$this->select("client_id")->get()[0]["client_id"]);
        $client_id = $this->select("client_id")->get()[0]["client_id"];
        logger("Id Cliente: ".$client_id);
        $client_name = Client::find($client_id)["nome"];
        logger("Nome Cliente: ".$client_name);
        return $client_name;
    }

    public function scopeInfo($query, $input)
    {
        return $query->hasMany('App\Order')->selectRaw('table_id, food_id, COUNT(food_id) AS total')->groupBy('food_id')->get();
    }
}
