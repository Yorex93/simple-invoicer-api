<?php

namespace App;

use App\Models\Company;
use App\Models\Invoice;
use App\Models\Profile;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    public function company(){
    	return $this->hasOne(Company::class);
    }


    public function profile(){
    	return $this->hasOne(Profile::class);
    }

    public function invoices(){
    	return $this->hasMany(Invoice::class);
    }
}
