<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    public $timestamps = false;

    public function payments(){
        return $this->hasMany(SalePayment::class, "sale_id", "id")->get();
    }

    public function products(){
        return $this->hasMany(SaleProduct::class, "sale_id", "id")->get();
    }
}
