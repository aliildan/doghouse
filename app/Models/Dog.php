<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Dog model class.
 */
class Dog extends Model
{
    use HasFactory;

    protected $table = 'dogs';
    protected $fillable = [
        'name',
        'breed',
        'age'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

}
