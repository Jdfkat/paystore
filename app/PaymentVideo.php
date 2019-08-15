<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentVideo extends Model
{
    //
    protected $fillable = ['user_id', 'video_id', 'payment_info', 'payment_status', 'payment_amount'];
}
