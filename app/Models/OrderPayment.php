<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderPayment extends Model
{
    public function created_user(): BelongsTo{
        return $this->belongsTo(User::class, 'created_by');
    }
}
