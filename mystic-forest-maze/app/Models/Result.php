<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    use HasFactory;

    protected $fillable = [
        'completion_time',
        'date',
        'team',
    ];

    public function scopeByYear($query, $year)
    {
        return $query->whereYear('date', $year);
    }
}
