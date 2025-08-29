<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use carbon\Carbon;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'created_at' => 'datetime:Y-m-d H:i:s'
    ];

    public function orders(){
        return $this->hasMany(Order::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }


    public function hasActiveSubscription($subcategory)
    {
        // return true;
        $subscriptions = $this->orders;

    
        if (!count($subscriptions)) {
            return false;
        }

        $subscription = $subscriptions->filter(fn($item)=>$item->subcategory_id == $subcategory->id and $item->status=='completed')->sortByDesc('id')->first();
        
        // dd($subscription->expires_at);
        $now = Carbon::now();
        $expiryDate = Carbon::parse($subscription?->expires_at);
        
        
        return $subscription?->status === 'completed' && $now->lt($expiryDate);
    }


    public function userExamAnswers(){
        return $this->hasMany(UserExam::class,'user_id');
    }

    public function userExamStarts(){
        return $this->hasMany(UserExamStart::class);
    }
}
