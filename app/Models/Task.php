<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model {
    protected $fillable = [
        'household_id', 'user_id', 'created_by',
        'title', 'description', 'category',
        'recurrence', 'start_date', 'start_time', 'status'
    ];

    // task is assigned to this user
    public function assignedTo() {
        return $this->belongsTo(User::class, 'user_id');
    }

    // task was created by this user
    public function createdBy() {
        return $this->belongsTo(User::class, 'created_by');
    }
}