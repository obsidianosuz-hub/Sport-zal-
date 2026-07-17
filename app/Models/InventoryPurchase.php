<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryPurchase extends Model
{
    protected $fillable = ['product_id', 'user_id', 'quantity', 'total_cost', 'date'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
