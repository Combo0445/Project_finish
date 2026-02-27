<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Auth\Authenticatable;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Model implements AuthenticatableContract
{
    use Authenticatable, Notifiable, Authorizable, SoftDeletes;

    protected $table = 'users'; // ชื่อตาราง
    protected $primaryKey = 'ID_User'; // primary key ที่คุณกำหนดในฐานข้อมูล
    protected $fillable = [
        'Username',
        'Password',
        'ID_Personnel',
        'Type_Personnel',
        'Name_User',
        'Type_Doctor',
        'Email',
        'Address',
        'Phone',
        'Image_User',
        'line_token',
        'is_admin_permanent'
    ];
    public $timestamps = false;

    protected $hidden = [
        'Password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the user's avatar URL.
     * Uses UI-Avatars for falling back if no physical image exists.
     */
    public function getImageUrlAttribute()
    {
        if ($this->Image_User && file_exists(public_path($this->Image_User))) {
            return asset($this->Image_User);
        }

        // Fallback to UI Avatars with Name_User or Username
        $name = $this->Name_User ?: $this->Username;
        $background = str_pad(dechex(mt_rand(0xFFFFFF / 2, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
        return "https://ui-avatars.com/api/?name=" . urlencode($name) . "&background={$background}&color=fff&size=128";
    }
}
