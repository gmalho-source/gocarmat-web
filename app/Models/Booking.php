<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}
