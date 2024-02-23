<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address',
        'number',
        'neighborhood',
        'city',
        'state',
        'cpf_cnpj',
    ];

    protected $hidden = [
        'password',
    ];
}
