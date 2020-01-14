<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'shipping_price',
        'created_at'
    ];

    /**
     * Get the customer that owns the shipping address.
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the items for the order.
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get the discounts for the order.
     */
    public function discounts()
    {
        return $this->hasMany(OrderDiscount::class);
    }
}
