<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Favorite extends Model
{
    protected $fillable = [
        'user_id',
        'favoritable_type',
        'favoritable_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function favoritable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Returns a normalized alias of the favoritable type.
     */
    public function typeAlias(): string
    {
        if ($this->favoritable_type === User::class) {
            return 'restaurant';
        }
        if ($this->favoritable_type === Property::class) {
            return 'property';
        }
        return $this->favoritable_type;
    }
}