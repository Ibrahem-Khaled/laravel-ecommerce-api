<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserNotificationReader extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'notification_id',
    ];
}
