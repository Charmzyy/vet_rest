<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    use HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = 'users';
    protected $primarykey = 'id';
    
    public $incrementing = false;
    protected $fillable = [
        'firstname',
        'lastname',
        'email',
        'password',
        'confirm_password',
        'phone',
        'is_available'
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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function myAppointmentsAsOwner(){
        return $this->hasMany(Appointment::class,'owner_id');
    }
    public function myAppointmentAsDoctor(){
        return $this->hasMany(Appointment::class, 'doc_id');
    }

    public function mypets(){
        return $this->hasMany(Pet::class);
    }

    public function mydept(){
        return $this->belongsTo(Department::class);
    }
}
