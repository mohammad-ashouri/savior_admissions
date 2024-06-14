<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Lab404\Impersonate\Models\Impersonate;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, hasPermissions, hasRoles, Impersonate;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'mobile',
        'email',
        'password',
        'type',
        'status',
        'personal_image',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'created_at',
        'updated_at',
        'deleted_at',
        'remember_token',
        'status',
        'email_verified_at'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function getSchoolIdAttribute()
    {
        $additionalInfo = json_decode($this->attributes['additional_information'], true);

        return isset($additionalInfo['school_id']) ? $additionalInfo['school_id'] : null;
    }

    public function generalInformationInfo()
    {
        return $this->belongsTo(GeneralInformation::class, 'id', 'user_id');
    }

    public function canImpersonate()
    {
        // Check if the user is an admin or has the role of Super Admin
        return $this->hasRoles('Super Admin');
    }

    public function hasRoles($role)
    {
        // Implement your logic here to check if the user has the specified role
        // For example, if you're using Laravel's built-in roles and permissions,
        // you can use something like:
        return $this->hasRole($role);
    }
}
