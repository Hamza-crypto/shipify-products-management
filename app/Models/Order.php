<?php

namespace App\Models;

use App\Traits\Encryptable;
use Carbon\Carbon;
use Facade\FlareClient\Report;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;

class Order extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'user_id',
        'type',
        'card_number',
        'pin',
        'month',
        'year',
        'cvc',
        'amount',
        'status',
        'processed_by',
        'transaction_id',
        'tag_id',
        'balance_screenshot',
        'status_update_reason',
    ];



    protected $dates = [
        'created_at',
        'updated_at',
        'status_updated_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class)
            ->select(['id', 'name', 'email', 'role', 'parent_id']);
    }

    public function gateway()
    {
        return $this->belongsTo(Gateway::class, 'processed_by');
    }

    public function screenshot()
    {
        return $this->hasOne(Screenshot::class);
    }

    public function activities()
    {
        return $this->hasMany(order_activities::class);
    }

    public function status()
    {
        return $this->status ?? '';
    }

    public function paid_status()
    {
        return $this->paid_status ?? '';
    }

    public function used_status()
    {
        return $this->used_status ?? '';
    }

    public function getStatusColor()
    {
        if ($this->status == 'declined') return 'danger';
        if ($this->status == 'accepted') return 'success';
        if ($this->status == 'pending') return 'warning';
        if ($this->status == 'void') return 'info';
        if ($this->status == 'canceled') return 'secondary';

        return '';
    }

    public function getPaidStatusColor()
    {
        if ($this->paid_status == 'paid') return 'success';
        return '';
    }

    public function getUsedStatusColor()
    {
        if ($this->used_status == 'used') return 'success';
        return '';
    }

    public function scopeFilters($query, $request)
    {
        if (isset($request['paid_status']) && $request['paid_status' ] != -100 && $request['paid_status'] != 'undefined') {
            $query->where('paid_status', $request['paid_status']);
        }

        if (isset($request['status']) && $request['status'] != -100) {
            $search = $request['status'];
            $query->where('status', $search);
        }

        if (isset($request['daterange'])) {
            $dateRange = explode(' - ', $request['daterange']);
            $query->whereBetween('orders.created_at', [Carbon::parse($dateRange[0])->format('Y-m-d'), Carbon::parse($dateRange[1])->format('Y-m-d')]);
        }

        if (isset($request['user']) && $request['user'] != -100 && $request['user'] != 'undefined') {
            $query->where('user_id', '=', $request['user']);
        }

        if (isset($request['used_status']) && $request['used_status'] != -100 && $request['used_status'] != 'undefined') {
            $query->where('used_status', $request['used_status']);
        }

        if (isset($request['gateway']) && $request['gateway'] != '999' && $request['gateway'] != 'undefined') {
            if ($request['gateway'] == 0) {
                $query->whereIn('processed_by', ['0', '']);
            } else {
                $query->where('processed_by', $request['gateway']);
            }

        }

        $user = Auth()->user();

        if ($user->role == 'customer') {  // Show only cards which are relevant to customer e.g. Colin
            $query->whereIn('processed_by', ['0', '']);
        } elseif ($user->role == 'manager') {

            // Getting records for customer
            $sub_users = User::where('parent_id', $user->id)->get();
            $query->whereIn('user_id', $sub_users->pluck(['id'])->push($user->id));

            // Applying filter
            if (isset($request['tag']) && $request['tag'] != '999' && $request['tag'] != 'undefined') {
                $query->where('tag_id', $request['tag']);
            }

        }

        $query->orderBy('created_at', 'desc');
    }

    public function routeNotificationForSlack($notification)
    {
        return env('SLACK_WEB_HOOK');
    }
}
