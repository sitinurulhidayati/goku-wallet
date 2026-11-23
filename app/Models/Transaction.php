<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // 1. Import trait ini
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory; // 2. Gunakan trait ini di dalam class

    // Ini penting agar Laravel tidak memblokir data saat proses input
    protected $fillable = ['user_id', 'type', 'amount', 'desc', 'date'];
}