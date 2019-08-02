<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleProduct extends Model
{
    public $timestamps = false;

    public function sale(){
        return $this->belongsTo(Sale::class, "sale_id", "id")->get();
    }
}
