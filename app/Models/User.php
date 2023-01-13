<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public $rate = 89;
    public $default_chat_id = '-1001456845228';

    public function canImpersonate()
    {
        // For example
        return $this->role == 'admin';
    }


    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'email_verified_at',
        'parent_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function tags()
    {
        return $this->hasMany(Tag::class)
            ->where('user_id', auth()->id());
    }

    public function messages()
    {
        return $this->hasMany(Message::class)
            ->where('user_id', auth()->id());
    }

    public function user_gateway_name()
    {
        return $this->whereHas('metas', function ($q) {
            $q->where('meta_key', 'gateway');
        });
    }

    public function user_orders()
    {
        return $this->hasMany(Order::class)
            ->where('user_id', auth()->id());
    }

    public function accepted_orders()
    {
        return $this->hasMany(Order::class)
            ->where('user_id', auth()->id())
            ->where('status', 'accepted');
    }

    public function rejected_orders()
    {
        return $this->hasMany(Order::class)
            ->where('user_id', auth()->id())
            ->where('status', 'declined');
    }

    public function pending_orders()
    {
        return $this->hasMany(Order::class)
            ->where('user_id', auth()->id())
            ->where('status', 'pending');
    }

    public function metas()
    {
        return $this->hasMany(UserMeta::class);
    }

    public function usdt_address()
    {
        return $this->metas->where('meta_key', 'usdt_address')->pluck('meta_value')->first() ?? '';
    }

    public function btc_address()
    {
        return $this->metas->where('meta_key', 'btc_address')->pluck('meta_value')->first() ?? '';
    }

    public function trc_address()
    {
        return $this->metas->where('meta_key', 'trc_address')->pluck('meta_value')->first() ?? '';
    }

    public function rate()
    {
        return $this->metas->where('meta_key', 'rate')->pluck('meta_value')->first() ?? $this->rate;
    }


    public function gateway()
    {
        return $this->metas->where('meta_key', 'gateway')->pluck('meta_value')->first() ?? '';
    }


    public function gateway_name()
    {
        $gateway_id = $this->metas->where('meta_key', 'gateway')->pluck('meta_value')->first() ?? '';
        $gateway = Gateway::find($gateway_id);
        if ($gateway){
            return $gateway->title;
        }

        return 'colin';
    }

    public function channel_id()
    {
        return $this->metas->where('meta_key', 'channel_id')->pluck('meta_value')->first() ?? $this->default_chat_id;
    }

    public function paxful_api_key()
    {
        return $this->metas->where('meta_key', 'paxful_api_key')->pluck('meta_value')->first() ?? '';
    }

    public function paxful_api_secret()
    {
        return $this->metas->where('meta_key', 'paxful_api_secret')->pluck('meta_value')->first() ?? '';
    }

    public function paxful_token()
    {
        return $this->metas->where('meta_key', 'paxful_token')->pluck('meta_value')->first() ?? '';
    }

    public function payable_section_visibility_status()
    {
        return $this->metas->where('meta_key', 'payable_visible')->pluck('meta_value')->first() ?? 1;
    }

    public function order_category()
    {
        return $this->metas->where('meta_key', 'order_category')->pluck('meta_value')->first() ?? '';
    }

    public function availability_status()
    {
        return $this->metas->where('meta_key', 'availability')->pluck('meta_value')->first() ?? '';
    }


    public function scopeFilters($query, $request)
    {
        if (isset($request['status']) && $request['status'] != -100) {
            $search = $request['status'];
            $query->where('status', $search);
        }

        if (isset($request['daterange'])) {
            $dateRange = explode(' - ', $request['daterange']);
            $query->whereBetween('orders.created_at', [Carbon::parse($dateRange[0])->format('Y-m-d'), Carbon::parse($dateRange[1])->format('Y-m-d')]);
        }

        if (isset($request['user']) && $request['user'] != -100) {
            $query->where('email', '=', $request['user']);
        }
    }


}
