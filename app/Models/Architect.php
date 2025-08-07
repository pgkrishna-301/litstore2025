<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Architect extends Model {
    use HasFactory;

    protected $fillable = [
        'select_architect',
        'name',
        'firm_name',
        'email',
        'ph_no',
        'shipping_address',
        'status',
        
    ];

    /**
     * Get the profession that this architect belongs to.
     */
    public function profession()
    {
        return $this->belongsTo(Profession::class, 'select_architect', 'id');
    }

    /**
     * Get the cart items that belong to this architect (customer).
     */
    public function cartItems()
    {
        return $this->hasMany(CartItem::class, 'customer_id');
    }

    /**
     * Get the order details that belong to this architect (customer).
     */
    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class, 'customer_id');
    }
}
