<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Customer;

class Order extends Model
{
    protected $fillable = ['customer_id', 'total_amount', 'status', 'payment_method', 'payment_status'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
