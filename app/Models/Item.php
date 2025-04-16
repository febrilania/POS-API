<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;
    protected $fillable = [
        'unit_id',
        'category_id',
        'name',
        'barcode',
        'price',
        'stock'
    ];

    public function unit(){
        return $this->belongsTo(Unit::class);
    }

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function transaction_details(){
        return $this->hasMany(TransactionDetail::class);
    }

}
