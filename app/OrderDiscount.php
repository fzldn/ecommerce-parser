<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderDiscount extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type',
        'value',
        'priority',
    ];

    /**
     * Get the order that owns the discount.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
