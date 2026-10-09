<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GasOrder extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'read_at' => 'datetime',
        ];
    }
}
