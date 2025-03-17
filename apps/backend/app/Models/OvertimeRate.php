<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OvertimeRate extends Model
{
    use HasFactory;

    protected $fillable = [
        'overtime_type_id', 'hours_threshold', 'multiplier'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function overtimeType()
    {
        return $this->belongsTo(OvertimeType::class);
    }
}
