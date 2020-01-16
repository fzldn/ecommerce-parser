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
        'id',
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

    /**
     * Scope a query to search specified customer.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $search
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch($query, $search)
    {
        if (!$search) {
            return $query;
        }

        $likeSearch = "%{$search}%";
        return $query
            ->where(function ($q) use ($likeSearch) {
                $q
                    ->where('first_name', 'like', $likeSearch)
                    ->orWhere('last_name', 'like', $likeSearch)
                    ->orWhere('email', 'like', $likeSearch)
                    ->orWhere('phone', 'like', $likeSearch);
            })
            ->orWhereHas('shippingAddress', function ($q) use ($search, $likeSearch) {
                $q
                    ->where('street', 'like', $likeSearch)
                    ->orWhere('postcode', $search)
                    ->orWhere('suburb', 'like', $likeSearch)
                    ->orWhere('state', 'like', $likeSearch);
            });
    }
}
