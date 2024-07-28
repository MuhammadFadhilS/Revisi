<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    protected $fillable = [
        'kode_supplier', // Add this line
        'name',
        'phone',
        'product_name',
        'product_brand',
        'quantity',
        'price',
        'expired',
        'payment_proof',
    ];
}
