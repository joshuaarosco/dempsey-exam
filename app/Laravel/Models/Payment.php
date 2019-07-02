<?php

namespace App\Laravel\Models;

use App\Laravel\Models\User;
use App\Laravel\Models\StudentInfo;
use App\Laravel\Models\StudentRequirement;

use App\Laravel\Traits\DateFormatterTrait;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Laravel\Models\Security\SecurityQuestion;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Helper, Input, DB;

class Payment extends Authenticatable
{
    use Notifiable, SoftDeletes, DateFormatterTrait;

    protected $table = "payment";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'amount',
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];
    
}
