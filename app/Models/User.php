<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        "firstname",
        "lastname",
        "username",
        "email",
        "account_level",
        "location",
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function account_level(): BelongsTo
    {
        return $this->belongsTo(AccountLevel::class);
    }

    public static function restricted($module_id)
    {
        $account_level_id   = Auth::id();
        $account_level      = AccountLevel::find($account_level_id);
        $module_arr         = $account_level->module_ids ? explode(",", $account_level->module_ids) : [];

        return !in_array($module_id, $module_arr);
    }
}
