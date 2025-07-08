<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $fillable = ['user_id', 'subscription_plan_id', 'starts_at', 'ends_at', 'amount', 'downloads_used', 'papers_used'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(SubscriptionPlan::class, 'subscription_plan_id');
    }

    public function isActive()
    {
        return now()->between($this->starts_at, $this->ends_at);
    }

    public function institution()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
