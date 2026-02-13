<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderInstructionNote extends Model
{
    public function order(): BelongsTo{
        return $this->belongsTo(Order::class);
    }
    
    public function created_user(): BelongsTo{
        return $this->belongsTo(User::class, "created_by");
    }

    public function updated_user(): BelongsTo{
        return $this->belongsTo(User::class, "updated_by");
    }
}
