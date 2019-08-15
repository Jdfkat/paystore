<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TestService;

class TestControler extends Controller
{
    //
    protected $testservice;

    public function __construct(TestService $testservice)
    {
    	$this->testservice = $testservice;
    }

    public function success(Request $request){

    	$custom = json_decode($request->get('custom'), true);

		if($custom['reference_type'] == 'video'){
			//call video service
			(new \App\Services\VideoPaymentService)->success($request);

		} elseif($custom['reference_type'] == 'item') {
			//call item service
			(new \App\Services\ItemPaymentService)->success($request);

		} else {
			throw new Exception('invalid data');
		}

    	//$this->testservice->success($request);
    }

    public function ipn(){
    	return 'Message from API route';
    }
}
