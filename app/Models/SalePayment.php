<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalePayment extends Model
{
    public function sale(){
        return $this->belongsTo(Sale::class)->get();
    }
}
