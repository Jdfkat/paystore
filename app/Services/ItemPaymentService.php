<?php

namespace App\Services;

use Auth;
use App\Item;
use App\PaymentLog;
use App\PaymentVideo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


/**
 * Class ItemPaymentService
 * @package App\Services
 */

class ItemPaymentService
{

	public function success(Request $request){
        try {

            $data = $request->all();
            Storage::append('paypalrequestservice.txt', json_encode($data['payment_status']) . ' Amount : ' . json_encode($data['payment_gross']));

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


            $custom = json_decode(
                $request->input('custom'),
                JSON_OBJECT_AS_ARRAY
            );

            /* Getting the user and video details through custom variable */
            //$video = Video::findOrFail($custom['video_id']);
            //$user_id = $custom['user_id'];

            $reference_type = $custom['reference_type'];
            $reference_id = $custom['reference_id'];

            //are we verified? If so, let's process the IPN
            if (strpos($curl_result, "VERIFIED")!==false)
            {
                Storage::append('paypal.txt', $curl_result . '<br />Data: ' . $ipnSerialized . 'END----<br />' );
                $valid = true;

                $paymentlog = new PaymentLog;
                $paymentlog->user_id = 1;
                $paymentlog->item_id = $custom['reference_id'];
                $paymentlog->payment_amount = $data['payment_gross'];
                $paymentlog->payment_status = $data['payment_status'];
                $paymentlog->payment_info = json_encode($data);
                $paymentlog->save();

                //if payment_status = Pending or Completed
                //attach item purchased to user.
                //Otherwise display an appropriate message

            } else {
                $valid = false;
                Storage::append('paypalError.txt', $curl_err);
            }
            
       } catch (\Exception $e) {
            dd($e);
       }
	}

}
