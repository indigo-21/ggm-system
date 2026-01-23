<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    public function customer(): BelongsTo{
        return  $this->belongsTo(Customer::class);  
    }

    public function order_cost(): HasOne {
        return $this->hasOne(OrderCost::class);
    }

    public function order_note(): HasOne{
        return $this->hasOne(OrderNote::class);
    }

    public function cemetery(): BelongsTo{
        return $this->belongsTo(Cemetery::class);
    }

    public function user(): BelongsTo{
        return $this->belongsTo(User::class, 'created_by');
    }
}
