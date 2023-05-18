<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Assessment
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\AssessmentFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Assessment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Assessment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Assessment query()
 * @method static \Illuminate\Database\Eloquent\Builder|Assessment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Assessment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Assessment whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Assessment extends Model
{
    use HasFactory;

    protected $fillable = ['time','is_publish'];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function topic(): BelongsTo
    {
        return $this->belongsTo(Topic::class);
    }

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }
}
