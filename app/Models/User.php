<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class User extends Authenticatable implements HasMedia
{
use HasApiTokens, HasFactory, Notifiable, HasRoles,InteractsWithMedia;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    public static $registerRules = [
        'name' => 'required',
        'email' => 'required|unique:users|email',
        'mobile' => 'required|unique:users|starts_with:+|min:11|numeric',
        'password' => 'required|min:6',
    ];

    public static $loginRules = [
         'email' => 'required',
         'password' => 'required|min:6'
    ];
    
    public static $customMessagesForRegister = [
        'mobile.starts_with' => 'The mobile number must start with a "+" sign.',
    ];

    public static $customMessagesForLogin = [
       'email.required' => 'Please provide email or phone number',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
