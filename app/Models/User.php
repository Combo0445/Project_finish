<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Auth\Authenticatable;
use Illuminate\Foundation\Auth\Access\Authorizable;

/**
 * User model representing the system users (Admin, Doctor, Staff).
 *
 * @property int $ID_User
 * @property string $Username
 * @property string $Password
 * @property int $ID_Personnel
 * @property string $Type_Personnel
 * @property string $Name_User
 * @property string|null $Type_Doctor
 * @property string $Email
 * @property string|null $Address
 * @property string|null $Phone
 * @property string|null $Image_User
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\BarthelAdl[] $barthel_adls
 */
class User extends Model implements AuthenticatableContract
{
    use Authenticatable, Notifiable, Authorizable;

    protected $table = 'users'; // Table name
    protected $primaryKey = 'ID_User'; // Custom primary key
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
        'Image_User'
    ];
    public $timestamps = false;

    protected $hidden = [
        'Password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    const TYPE_ADMIN = 'Admin';
    const TYPE_DOCTOR = 'Doctor';
    const TYPE_STAFF = 'Staff'; // Conceptualized as Nurse

    public function isAdmin()
    {
        return $this->Type_Personnel === self::TYPE_ADMIN;
    }

    public function isDoctor()
    {
        return $this->Type_Personnel === self::TYPE_DOCTOR;
    }

    public function isNurse()
    {
        return $this->Type_Personnel === self::TYPE_STAFF;
    }

    public function barthel_adls()
    {
        return $this->hasMany(BarthelAdl::class, 'ID_User', 'ID_User');
    }
}
