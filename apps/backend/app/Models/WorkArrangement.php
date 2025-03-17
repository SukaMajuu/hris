<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkArrangement extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'type', 'is_default'
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function attendanceLogs()
    {
        return $this->hasMany(AttendanceLog::class);
    }
}
