<?php

namespace App\Model;

use App\Model\Friend;
use App\Model\Notification;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $fillable = [
        'first_name', 'last_name'
    ];

}
