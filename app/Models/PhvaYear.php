<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PhvaYear extends Model
{
    protected $fillable = ['year'];

    public function matrices()
    {
        return $this->hasMany(PhvaMatrix::class);
    }

    public function dofas()
    {
        return $this->hasMany(Dofa::class);
    }
}
