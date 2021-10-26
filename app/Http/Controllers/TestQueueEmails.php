<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMailable;
use App\Jobs\SendEmailJob;
use App\Models\User;
use Carbon\Carbon;

class TestQueueEmails extends Controller
{
    public function sendTestEmails(Request $request){
        $data = $request->all();       
        $emailJob = (new SendEmailJob($data))->delay(Carbon::now());
        dispatch(($emailJob)->onQueue('high'));
        return response()->json(['status'=>'200','message'=>'email sent'],200);
    }

    public function sendEmail(){
        Mail::to('sweksha.vdoit@gmail.com')->send(new SendMailable());
        echo 'email sent';
    }
}
