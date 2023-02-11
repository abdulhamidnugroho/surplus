<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    public function category_product()
    {
        return $this->hasMany(CategoryProduct::class, 'product_id');
    }

    public function product_image()
    {
        return $this->hasMany(ProductImage::class, 'product_id');
    }
}
