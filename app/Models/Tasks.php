<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tasks extends Model
{
    // Kolom yang boleh diisi secara massal
    protected $fillable = [
        'title',
        'category',
        'is_done',
        'deadline',
        'user_id'
    ];
}
