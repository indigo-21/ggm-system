<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class BurialSocietyOrganization extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ["name","cemetery_id"];

    public function user(): BelongsTo{
        return $this->belongsTo(User::class, "created_by");
    }

    public function cemetery(): Belongsto{
        return $this->belongsTo(Cemetery::class);
    }
}
