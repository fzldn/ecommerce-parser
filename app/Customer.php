<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
    ];

    /**
     * Get the shiping address record associated with the customer.
     */
    public function shippingAddress()
    {
        return $this->hasOne(ShippingAddress::class);
    }
}
