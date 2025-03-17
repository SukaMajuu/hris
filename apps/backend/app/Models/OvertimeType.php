<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OvertimeType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'regulation_type', 'description', 'active'
    ];

    protected $casts = [
        'active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function rates()
    {
        return $this->hasMany(OvertimeRate::class);
    }

    public function requests()
    {
        return $this->hasMany(OvertimeRequest::class);
    }
}
