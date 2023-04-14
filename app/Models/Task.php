<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
    ];

    protected $casts = [
        'is_done' => 'boolean',
    ];

    protected $hidden = [
        'updated_at',
    ];
}
