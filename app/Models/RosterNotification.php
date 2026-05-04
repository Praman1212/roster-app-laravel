<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RosterNotification extends Model {
    protected $table    = 'roster_notifications';
    protected $fillable = ['user_id', 'task_id', 'message', 'type', 'read'];
}