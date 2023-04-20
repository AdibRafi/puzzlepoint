<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Option extends Model
{
    use HasFactory;

    protected $fillable = ['group_distribution', 'time_method', 'tm1', 'tm2', 'tm3', 'tm4', 'tm5', 'tm6'];

    public function topic(): BelongsTo
    {
        return $this->belongsTo(Topic::class);
    }
}
