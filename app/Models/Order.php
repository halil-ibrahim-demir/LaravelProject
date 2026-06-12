<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'phone',
        'address1',
        'address2',
        'city',
        'state',
        'country',
        'zipcode',
        'total_amount',
        'payment_method',
        'payment_status',
        'status',
        'tracking_no',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
