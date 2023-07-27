<?php

namespace App\Models;

use App\Models\Administration;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MazeInfo extends Model
{
    use HasFactory;

    protected $fillable = [
        'logo',
        'bg-color',
        'font'
    ];

    public function administration() {
        return $this->belongsTo(Administration::class);
    }
}
