<?php

namespace App\Http\Controllers;

use Auth;
use App\Item;
use App\PaymentLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PaypalController extends Controller
{
    //
    public function notify(Request $request)
    {
        try {
            $data = $request->all();
            Storage::append('paypalrequest.txt', json_encode($data['payment_status']) . ' Amount : ' . json_encode($data['payment_gross']));

            // Read the post from PayPal system and add 'cmd'   
            $req = 'cmd=_notify-validate';   
  
            // Add ipn data to array  
            $ipnEmail = '';
            $ipnData = array();
            foreach ($data as $key => $value)   
            {   
                $value = urlencode(stripslashes($value));   
                $req .= "&" . $key . "=" . $value;   
                $ipnEmail .= $key . " = " . urldecode($value) . '<br />';  
                $ipnData[$key] = urldecode($value);
            }

            // Store IPN data serialized for RAW data storage later
            $ipnSerialized = serialize($ipnData);

            // Validate IPN with PayPal using curl
            $curl_result=$curl_err='';
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://ipnpb.sandbox.paypal.com/cgi-bin/webscr');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/x-www-form-urlencoded", "Content-Length: " . strlen($req)));
            curl_setopt($ch, CURLOPT_HEADER , 0);   
            curl_setopt($ch, CURLOPT_VERBOSE, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);

            $curl_result = curl_exec($ch);
            $curl_err = curl_error($ch);
            curl_close($ch);

            //are we verified? If so, let's process the IPN
            if (strpos($curl_result, "VERIFIED")!==false)
            {
                Storage::append('paypal.txt', $curl_result . '<br />Data: ' . $ipnSerialized . 'END----<br />' );
                $valid = true;
                
                //if payment_status = Pending or Completed
                //attach item purchased to user.
                //Otherwise display an appropriate message

                //if txn_id already exist in the db, don't insert record again
                //send a hearder 200 to paypal.

                //Insert into db
                $paymentlog = new PaymentLog;
                $paymentlog->user_id = 1;
                $paymentlog->item_id = $data['custom'];
                $paymentlog->payment_amount = $data['payment_gross'];
                $paymentlog->payment_status = $data['payment_status'];
                $paymentlog->payment_info = json_encode($data);
                $paymentlog->save();

                //now to avoid to enter another record,
                //check if same txn_id exists in db, if it does don't insert
                //also send to paypal an empty header so that no message is send
                /*
                $input = [];
                $input['user_id'] = 1;
                $input['item_id'] = $data['custom'];
                $input['payment_info'] = $ipnSerialized;
                PaymentLog::create();
                */

                //process IPN

                //insert result in payment_logs db
            } else {
                $valid = false;
                Storage::append('paypalError.txt', $curl_err);
            }
            
       } catch (\Exception $e) {
            dd($e);
       }
    }
}
