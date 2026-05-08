<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dofa extends Model
{
    protected $fillable = [
        'phva_year_id',
        'proceso',
        'responsable',
        'factor',
        'tipo',
        'descripcion',
        'probabilidad',
        'impacto',
        'color',
        'sort_order',
        'created_at'
    ];

    public function phvaYear()
    {
        return $this->belongsTo(PhvaYear::class);
    }
}
