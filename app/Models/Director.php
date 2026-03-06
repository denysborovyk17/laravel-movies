<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Movie> $movies
 * @property-read int|null $movies_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Director newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Director newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Director query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Director whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Director whereName($value)
 *
 * @mixin \Eloquent
 */
class Director extends Model
{
    protected $fillable = ['name'];

    public $timestamps = false;

    public function movies()
    {
        return $this->hasMany(Movie::class);
    }
}
