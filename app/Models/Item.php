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
}
