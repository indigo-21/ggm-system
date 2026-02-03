<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrderCost extends Model
{
    public function additionals(): HasMany{
        return $this->hasMany(OrderCostAdditional::class);
    }
}
