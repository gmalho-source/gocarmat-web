<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'newsletter_opt_in' => 'boolean',
            'read_at' => 'datetime',
        ];
    }

    public function office(): BelongsTo
    {
        return $this->belongsTo(Office::class);
    }
}
