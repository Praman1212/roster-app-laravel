<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable {
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'color', 'household_id'
    ];

    protected $hidden = ['password', 'remember_token'];

    // user belongs to one household
    public function household() {
        return $this->belongsTo(Household::class);
    }

    // user has many notifications
    public function rosterNotifications() {
        return $this->hasMany(RosterNotification::class);
    }
}