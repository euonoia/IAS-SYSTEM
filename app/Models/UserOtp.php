<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserOtp extends Model
{
    protected $table = 'user_otps_ums';
    public $timestamps = false; // Dahil manual ang created_at sa schema mo

    protected $fillable = [
        'user_id',
        'otp_code',
        'expires_at',
        'is_used'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}