<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
use App\Models\ProductImage;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'quantity',
        'type',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'type', 'id');
    }

    public function images()
    {
        return $this->belongsTo(ProductImage::class, 'id', 'product_id');
    }

}
