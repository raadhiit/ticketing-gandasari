<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ErpSsoToken extends Model
{
    protected $fillable = [
        'token_hash',
        'user_id',
        'expires_at',
        'used_at',
        'erp_user_id',
        'ip_address',
        'user_agent',
    ];

    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
            'used_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isUsable(): bool
    {
        return is_null($this->used_at)
            && $this->expires_at->isFuture();
    }
}
