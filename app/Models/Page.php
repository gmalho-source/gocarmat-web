<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'content' => 'array',
            'published_at' => 'datetime',
            'show_in_menu' => 'boolean',
        ];
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published')
            ->where(fn ($q) => $q->whereNull('published_at')->orWhere('published_at', '<=', now()));
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function metaTitle(): string
    {
        return $this->meta_title ?: $this->title;
    }

    public function metaDescription(): ?string
    {
        return $this->meta_description;
    }
}
