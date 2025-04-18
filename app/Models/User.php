<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    

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

    public function projects() {
        return $this->hasMany(Project::class);
    }
    
    public function donations() {
        return $this->hasMany(Donation::class);
    }
    
    public function comments() {
        return $this->hasMany(Comment::class);
    }
    
    public function interests() {
        return $this->hasMany(UserInterest::class);
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isCreator()
    {
        return $this->role === 'creator';
    }

    public function isBacker()
    {
        return $this->role === 'backer';
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function favoriteProjects()
    {
        return $this->belongsToMany(Project::class, 'favorites');
    }
}
