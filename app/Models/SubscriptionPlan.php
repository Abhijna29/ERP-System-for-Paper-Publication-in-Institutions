<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    protected $fillable = ['name', 'duration', 'price', 'objective', 'summary', 'paper_limit', 'download_limit'];
}
