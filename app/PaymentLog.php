<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentLog extends Model
{
    //

    protected $fillable = ['user_id', 'item_id', 'payment_info', 'payment_status', 'payment_amount'];
}
