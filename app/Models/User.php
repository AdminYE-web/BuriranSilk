<?php

namespace App\Models;

use App\Notifications\VerifyEmailCustom;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;

    protected $primaryKey = 'user_id';

    protected $fillable = [
        'first_name',
        'last_name',
        'name',
        'email',
        'phone',
        'password',
        'avatar',
        'status',
        'term_policy',
        'receive_email',
        'email_verified_at',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'password' => 'hashed',
            'term_policy' => 'boolean',
            'receive_email' => 'boolean',
        ];
    }

    public function sendEmailVerificationNotification()
    {
        $url = URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(5),
            [
                'id' => $this->user_id,
                'hash' => sha1($this->email),
            ]
        );

        $this->notify(new VerifyEmailCustom($url));
    }

    public function socialAccounts()
    {
        return $this->hasMany(SocialAccount::class, 'user_id', 'user_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'user_id', 'user_id');
    }

    public function loginLogs()
    {
        return $this->hasMany(UserLoginLog::class, 'user_id', 'user_id');
    }

    public function recordLogin(Request $request, string $method = 'email'): void
    {
        $loggedInAt = now();

        $this->loginLogs()->create([
            'login_method' => $method,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'logged_in_at' => $loggedInAt,
        ]);

        $this->forceFill([
            'last_login_at' => $loggedInAt,
        ])->save();
    }

    public function contacts()
    {
        return $this->hasMany(UserContact::class, 'user_id', 'user_id');
    }

    public function mainContact()
    {
        return $this->hasOne(UserContact::class, 'user_id', 'user_id')
            ->where('is_main', 1);
    }

    public function addresses()
    {
        return $this->hasMany(UserAddress::class, 'user_id', 'user_id');
    }

    public function mainShippingAddress()
    {
        return $this->hasOne(UserAddress::class, 'user_id', 'user_id')
            ->where('address_type', 'shipping')
            ->where('is_main', 1);
    }

    public function mainBillingAddress()
    {
        return $this->hasOne(UserAddress::class, 'user_id', 'user_id')
            ->where('address_type', 'billing')
            ->where('is_main', 1);
    }
}
