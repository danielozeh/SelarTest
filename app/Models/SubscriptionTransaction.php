<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;

class SubscriptionTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'plan_id',
        'amount',
        'currency',
        'status',
        'payment_plan',
        'payment_method',
        'transaction_ref',
        'payment_ref'
    ];

    public function getCreatedAtAttribute($date)
    {
        return Carbon::createFromTimestamp(strtotime($date))->format('d F Y');
    }

    public function getUpdatedAtAttribute($date)
    {
        return Carbon::createFromTimestamp(strtotime($date))->format('d F Y');
    }

    public function plan() {
        return $this->belongsTo(Plan::class);
    }
}
