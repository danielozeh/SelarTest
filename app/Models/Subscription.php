<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'plan_id',
        'user_id',
        'amount',
        'currency',
        'payment_plan',
        'start_date',
        'due_date'
    ];
}
