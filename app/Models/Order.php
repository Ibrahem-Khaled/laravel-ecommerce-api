<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $appends = ['total_price'];
    protected $fillable = [
        'user_id',
        'lat',
        'lng',
        'shipping_cost',
        'tax',
        'payment_method',
        'status',
        'note',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'order_items', 'order_id', 'product_id')
            ->withPivot('quantity', 'price')
            ->withTimestamps();
    }


    //this accessors to get the total price of the order
    public function getTotalPriceAttribute()
    {
        return  $this->items()->sum('price');
    }
}
