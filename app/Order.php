<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Food;

class Order extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'table_id', 'provision_id', 'amount', 'add_percent',
    ];


    public function table()
    {
        return $this->belongsTo('App\Table', 'table_id');
    }

    public function food()
    {
        return $this->hasOne('App\Provision', 'id', 'provision_id')->get()[0];
    }


}
