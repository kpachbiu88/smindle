<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\hasMany;

class Order extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'address',
    ];

    public function basket(): hasMany
    {
        return $this->hasMany(BasketItem::class, 'order_id', 'id');
    }
}
