<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'profile_completed',
        'admin_verified',
    ];

     protected $attributes = [
        'role' => 'scientist',
        'profile_completed' => false,
        'admin_verified' => false,
    ];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
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
            'profile_completed' => 'boolean',
            'admin_verified' => 'boolean',
        ];
    }

    public function scientistProfile()
    {
        return $this->hasOne(Scientist::class);
    }

    public function availabilitySlots()
    {
        return $this->hasMany(Availability::class, 'scientist_id');
    }

    public function isScientist()
    {
        return $this->role === 'scientist';
    }

    public function canAccessAvailability()
    {
        return $this->profile_completed && $this->admin_verified;
    }
}
