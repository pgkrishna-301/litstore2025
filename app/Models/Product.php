<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'add_product';

    protected $fillable = [
        'product_name',
        'product_category',
        'mrp',
        'hns_code',
        'dimensions',
        'driver_output',
        'ip_rating',
        'body_color',
        'color_temp',
        'beam_angle',
        'cut_out',
        'description',
        'product_details',
        'banner_image',
        'add_image',
    ];

    protected $casts = [
        'body_color' => 'array',
        'color_temp' => 'array',
        'beam_angle' => 'array',
        'cut_out' => 'array',
        'add_image' => 'array',
        'product_details' => 'array',
    ];

    /**
     * Get the cart items that belong to this product.
     */
    public function cartItems()
    {
        return $this->hasMany(CartItem::class, 'product_id');
    }
}
