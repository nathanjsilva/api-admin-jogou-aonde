<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    /**
     * Método para definir o relacionamento com a tabela de produtos.
     * Isso permite que você acesse os produtos de uma categoria específica
     * usando algo como $category->products
     */
    public function products()
    {
        // Supondo que 'type' em Product corresponda ao 'id' em Category
        return $this->hasMany(Product::class, 'type', 'id');
    }
}
