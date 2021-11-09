<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;

class Plan extends Model
{
    use HasFactory;

    public function getCreatedAtAttribute($date)
    {
        return Carbon::createFromTimestamp(strtotime($date))->format('d F Y');
    }

    public function getUpdatedAtAttribute($date)
    {
        return Carbon::createFromTimestamp(strtotime($date))->format('d F Y');
    }

    public function variations() {
        return $this->hasMany(PlanVariation::class);
    }

    public function features() {
        return $this->hasMany(PlanFeatures::class);
    }
}
