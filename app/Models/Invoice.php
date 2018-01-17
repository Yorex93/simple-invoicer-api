<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $guarded = [];

    public function user(){
    	return $this->belongsTo(User::class);
    }

    public function items(){
    	return $this->hasMany(InvoiceItem::class);
    }
}
