<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class Question extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'text',
        'customer_id',
        'employee_id',
        'reply',

    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [

    ];

    protected $guarded = [

    ];


    public function customer() {
        return $this->belongsTo(Customer::class,'customer_id');
    }

    public function employee() {
        return $this->belongsTo(Customer::class,'employee_id');
    }



}
