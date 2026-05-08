<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $fillable = [
        'date',
        'name',
        'area',
        'consumption',
        'quantity',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
