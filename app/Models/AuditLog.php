<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'model_type',
        'model_id',
        'action',
        'user_id',
        'old_values',
        'new_values',
        'ip_address'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
