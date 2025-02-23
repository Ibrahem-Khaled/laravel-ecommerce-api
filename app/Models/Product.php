<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'status',
        'images',
        'price',
        'price_after_discount',
        'quantity',
        'views',

        'sub_category_id',
        'user_id',
    ];
    //this cast is used to convert the json string to array
    protected $casts = [
        'images' => 'array',
    ];

    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_items', 'product_id', 'order_id')
            ->withPivot('quantity', 'price')
            ->withTimestamps();
    }
}
