<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PhvaMatrix extends Model
{
    protected $fillable = ['phva_year_id', 'name', 'file_path', 'extension', 'drive_link', 'phase', 'category'];

    public function phvaYear()
    {
        return $this->belongsTo(PhvaYear::class);
    }
}
