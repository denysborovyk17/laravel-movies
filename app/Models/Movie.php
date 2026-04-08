<?php declare(strict_types=1);

namespace App\Models;

use App\Enums\MovieStatus;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $title
 * @property int|null $director_id
 * @property string|null $image
 * @property string|null $slug
 * @property string|null $description
 * @property string|null $year
 * @property string|null $genre
 * @property float|null $rating
 * @property MovieStatus $status
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Director|null $director
 * @property-read string|null $image_url
 *
 * @method static \Database\Factories\MovieFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Movie newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Movie newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Movie onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Movie published()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Movie query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Movie whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Movie whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Movie whereDirectorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Movie whereGenre($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Movie whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Movie whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Movie whereRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Movie whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Movie whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Movie whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Movie whereYear($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Movie withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Movie withoutTrashed()
 *
 * @mixin \Eloquent
 */
class Movie extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['title', 'slug', 'image', 'description', 'year', 'genre', 'rating', 'status', 'director_id'];

    protected $casts = [
        'rating' => 'float',
        'status' => MovieStatus::class,
    ];

    public $timestamps = false;

    public function director(): BelongsTo
    {
        return $this->belongsTo(Director::class);
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }

    #[Scope]
    protected function published(Builder $query): void
    {
        $query->where('status', MovieStatus::PUBLISHED->value);
    }
}
