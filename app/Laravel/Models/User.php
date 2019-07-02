<?php

namespace App\Laravel\Models;

use App\Laravel\Models\User;
use App\Laravel\Models\Payment;
use App\Laravel\Models\StudentInfo;
use App\Laravel\Models\StudentRequirement;

use App\Laravel\Traits\DateFormatterTrait;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Laravel\Models\Security\SecurityQuestion;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Helper, Input, DB;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes, DateFormatterTrait;

    protected $table = "user";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fname',
        'mname',
        'lname',
        'extname',
        'birthdate',
        'age',
        'civil_status',
        'gender',
        'province',
        'municipality_town',
        'zip_code',
        'barangay',
        'street',
        'contact_number',
        'email',
        'fathers_name',
        'fathers_occupation',
        'mothers_name',
        'mothers_occupation',
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token'
    ];


    /**
     * Search users that match a keyword.
     */
    public function scopeKeyword($query, $keyword) {
        if($keyword){
            return $query->where(function($query) use($keyword) {
                $query->where('fname', 'like', "%{$keyword}%")
                ->orWhere('lname', 'like', "%{$keyword}%")
                ->orWhere('birthdate', 'like', "%{$keyword}%")
                ->orWhere('contact_number', 'like', "%{$keyword}%")
                ->orWhere('email', 'like', "%{$keyword}%");
            });
        }
    }

    /**
     * payment
     */
    public function payment($user_id) {
        return Payment::where('user_id',$user_id)->sum('amount');
    }

    /**
     * payment history
     */
    public function payment_history($user_id) {
        return Payment::where('user_id',$user_id)->get();
    }
}
