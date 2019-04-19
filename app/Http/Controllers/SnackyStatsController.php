<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;

use Carbon\Carbon;
use App\SnackyStats;

class SnackyStatsController extends Controller
{
    
    public function index(Request $request){
   

        return View('index');
    }


    public function getAllData(Request $request){
        
        
        //dd($request->all());

        $fromDate = date('2019-04-16' . ' 00:00:00', time()); 
        $toDate = date('2019-04-17' . ' 23:59:59', time()); 
        $allData = DB::table('snacky_stats')
                    ->whereBetween('datumpje', array($fromDate, $toDate))->orderBy('desc')->get();
        
        
        return response()->json($allData);

        //return response(view('index',array('allData'=>$allData)),200, ['Content-Type' => 'application/json']);
    }

    public function getTodayPage(){
        $nowInAmsterdam =  Carbon::now('Europe/Amsterdam')->toDateString();
        
        $data = SnackyStats::whereDate('datumpje', '=' ,$nowInAmsterdam )->get();
        //$data = SnackyStats::where('created_at', '>=', $nowInAmsterdam . ' 00:00:00')->where('created_at', '<=', $nowInAmsterdam . ' 23:59:59');
        

        return View('getTodayPage');
        
    }


    public function getDataToday(){

        $nowInAmsterdam =  Carbon::now('Europe/Amsterdam')->toDateString();
        $today = DB::table('snacky_stats')->whereDate('datumpje', '=' ,$nowInAmsterdam)->orderByRaw('datumpje ASC')->get();
        //$today = SnackyStats::whereDate('datumpje', '=' ,$nowInAmsterdam )->get();

        return response()->json($today);
        
    }


    public function getDataYesterday(){
        $yesterdayInAmsterdam = Carbon::yesterday()->toDateString();
        $yesterdayData = SnackyStats::whereDate('datumpje', '=' , $yesterdayInAmsterdam)->orderByRaw('datumpje ASC')->get();
        return response()->json($yesterdayData);
    }

    public function getYesterdayPage(){

        //$nowInAmsterdam =  Carbon::now('Europe/Amsterdam')->format('Y-m-d');
        return view('getYesterdayPage');
    }


    public function LastsevenDaysPage(){
        
        return view('lastsevendays');

    }

    public function LastsevenDaysData(){
        $lastsevendays =  Carbon::now('Europe/Amsterdam')->subDays(7)->toDateString();
        $nowInAmsterdam =  Carbon::now('Europe/Amsterdam')->toDateString();

        $lastsevendaysData = SnackyStats::whereBetween('datumpje', [$lastsevendays, $nowInAmsterdam])->orderByRaw('datumpje ASC')->get();
        
        if($lastsevendaysData != Null){
            return response()->json($lastsevendaysData);
        }
    }


    public function last30days(){
        $last30daysData = Carbon::now('Europe/Amsterdam')->subDays(30)->toDateString();
        $nowInAmsterdam =  Carbon::now('Europe/Amsterdam')->toDateString();
        $last30days = SnackyStats::whereBetween('datumpje', [$last30daysData, $nowInAmsterdam])->orderByRaw('datumpje ASC')->get();
        
        if($last30days != Null){
            return response()->json($last30days);
        }
    }

    public function thismonth(){
        $thismonth = SnackyStats::where('datumpje', '>=', Carbon::now('Europe/Amsterdam')->startOfMonth())->orderByRaw('datumpje ASC')->get();
        
        return response()->json($thismonth);
    }

    public function lastmonth(){
        $lastmonth = SnackyStats::where('datumpje', '>=', Carbon::now('Europe/Amsterdam')->subMonth())->orderByRaw('datumpje ASC')->get();
        return response()->json($lastmonth);
    }

    public function checkOpenOrClosed(){
        $data = DB::table('snacky_stats')->latest('datumpje')->first();
        $dataJson = json_decode(json_encode($data),true);
        $open_or_closed_bool = $dataJson['open_or_closed'];

        return response()->json($open_or_closed_bool);
        
    }


}
