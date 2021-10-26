<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

use Carbon\Carbon;
use App\Models\Event;

class DateRangeFilterController extends Controller
{
    public function index()
    {
        return view('dashboard');
    }

    
    public function fetch_data(Request $request)
    {
        if(request()->ajax())
        {
            if(!empty($request->from_date))
            {
                $data = Event::whereBetween('event_date', array($request->from_date, $request->to_date))
                        ->where('status','=',1)->with('user')
                        ->get();
            }
            else
            {
                $data = Event::where('status','=',1)->with('user')
                        ->get();
            }
            return datatables()->of($data)->make(true);
        }
        
    }
}
