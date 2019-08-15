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

    	$this->testservice->success($request);
    }

    public function ipn(){
    	return 'Message from API route';
    }
}
