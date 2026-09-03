<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    protected $casts = [
        'date_of_death' => 'datetime',
        'consecration_date' => 'date',
        'fixing_date' => 'date',
        'grave_number_checked' => 'date',
        'invoice_date' => 'date',
        'is_tba' => 'boolean',
        'is_approx' => 'boolean',
        'is_asap' => 'boolean',
    ];

    /**
     * Quotes are orders that have no recorded payments yet.
     */
    public function scopeQuotes(Builder $query): Builder
    {
        return $query->doesntHave('payments');
    }

    /**
     * Orders are those that already have at least one payment.
     */
    public function scopeOrders(Builder $query): Builder
    {
        return $query->whereHas('payments');
    }

    /**
     * Restrict to records created within the given month/year.
     */
    public function scopeForPeriod(Builder $query, ?int $month, ?int $year): Builder
    {
        if ($month) {
            $query->whereMonth('created_at', $month);
        }

        if ($year) {
            $query->whereYear('created_at', $year);
        }

        return $query;
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function order_cost(): HasOne
    {
        return $this->hasOne(OrderCost::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(OrderPayment::class, 'order_id');
    }

    public function order_note(): HasOne
    {
        return $this->hasOne(OrderNote::class);
    }

    public function cemetery(): BelongsTo
    {
        return $this->belongsTo(Cemetery::class);
    }

    public function burial_society_organization(): BelongsTo
    {
        return $this->belongsTo(BurialSocietyOrganization::class);
    }

    public function grave_space(): BelongsTo
    {
        return $this->belongsTo(GraveSpace::class);
    }

    public function letter_type(): BelongsTo
    {
        return $this->belongsTo(LetterType::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function order_instruction_notes(): HasMany
    {
        return $this->hasMany(OrderInstructionNote::class);
    }

    public function order_inscription(): HasOne
    {
        return $this->hasOne(OrderInscription::class);
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'location_id');
    }

    public function order_files(): HasMany
    {
        return $this->hasMany(OrderFile::class, 'order_id');
    }

    public function order_emails(): HasMany
    {
        return $this->hasMany(OrderMail::class, 'order_id');
    }

    public function new_memorials(): HasMany
    {
        return $this->hasMany(OrderNewMemorial::class, 'order_id');
    }
}
