<?php

namespace App\Jobs;
use Illuminate\Http\Request;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMailable;
use App\Models\User;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Request $request)
    {
        $data = $request->all();
        $ids= explode(',',$data['id']);
        $users = User::whereIn('id',$ids)->get(['email','name'])->toArray(); 
       
        // Mail::queue('send', ['users' => $users], function($m) use ($users) {
        //     foreach ($users as $user) {
        //        $m->to($user['email'])->subject('YOUR SUBJECT GOES HERE');
        //     }
        // });      
        foreach($users as $user){
            Mail::to($user['email'])->send(new SendMailable());
        }
        //Mail::to('sweksha.vdoit@gmail.com')->send(new SendMailable());
    }
}
