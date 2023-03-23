<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Collection;

/**
 * App\Models\Topic
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\TopicFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Topic newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Topic newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Topic query()
 * @method static \Illuminate\Database\Eloquent\Builder|Topic whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Topic whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Topic whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Topic extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'date_time', 'no_of_modules', 'max_time_expert', 'max_time_jigsaw', 'status'];


    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class);
    }

    public function modules(): HasMany
    {
        return $this->hasMany(Module::class);
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function assessment(): HasOne
    {
        return $this->hasOne(Assessment::class);
    }

    public function option(): HasOne
    {
        return $this->hasOne(Option::class);
    }

    public function groups(): HasMany
    {
        return $this->hasMany(Group::class);
    }

    public function getUsers(): Collection
    {
        //todo: check if this right query
        return $this->classroom()->with(['users' => function ($query) {
            $query->where('users.id', '!=', $this->id); //don't want to include self
            $query->where('users.type', '=', 'student');
        }])->get()->pluck('users')->flatten();
    }

}
