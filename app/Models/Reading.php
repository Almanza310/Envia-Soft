<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reading extends Model
{
    /** @use HasFactory<\Database\Factories\ReadingFactory> */
    use HasFactory;

    protected $fillable = [
        'date',
        'warehouse',
        'type',
        'value',
        'consumption',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
