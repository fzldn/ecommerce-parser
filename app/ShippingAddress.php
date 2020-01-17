<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShippingAddress extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'customer_id',
        'street',
        'postcode',
        'suburb',
        'state',
    ];

    /**
     * Get the customer that owns the shipping address.
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
