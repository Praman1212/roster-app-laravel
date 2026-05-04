<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Household extends Model {
    protected $fillable = ['name', 'invite_code'];

    // one household has many users
    public function members() {
        return $this->hasMany(User::class);
    }

    // one household has many tasks
    public function tasks() {
        return $this->hasMany(Task::class);
    }
}