<?php

namespace App\Models;

use App\Enums\MovieStatus;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    protected $fillable = ['title', 'slug', 'image', 'description', 'year', 'genre', 'rating', 'status', 'director_id'];

    protected $casts = [
        'rating' => 'float',
        'status' => MovieStatus::class
    ];

    public $timestamps = false;

    public function director()
    {
        return $this->belongsTo(Director::class);
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }

    public function scopePublished($query)
    {
        if (auth()->check() && auth()->user()->isAdmin()) {
            return $query;
        }

        return $query->where('status', MovieStatus::PUBLISHED);
    }

}
