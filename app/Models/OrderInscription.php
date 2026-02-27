<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderInscription extends Model
{
    public function created_user(): BelongsTo{
        return $this->belongsTo(User::class, "created_by");
    }

    public function updated_user(): BelongsTo{
        return $this->belongsTo(User::class, "updated_by");
    }

    public function reviewed_user(): BelongsTo{
        return $this->belongsTo(User::class, "reviewed_by");
    }
}
