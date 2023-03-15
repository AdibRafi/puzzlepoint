<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Option extends Model
{
    use HasFactory;

    protected $fillable = ['group_distribution', 't1', 't2', 't3', 't4', 't5', 't6'];

    public function topic(): BelongsTo
    {
        return $this->belongsTo(Topic::class);
    }
}
