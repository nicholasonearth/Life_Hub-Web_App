<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Diary extends Model
{
    use HasFactory;

    // Ini adalah 'pintu izin' agar kolom database bisa diisi data
    protected $fillable = [
        'title', 
        'content', 
        'mood', 
        'user_id'
    ];
}