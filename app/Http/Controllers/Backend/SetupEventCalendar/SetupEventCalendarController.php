<?php

namespace App\Http\Controllers\Backend\SetupEventCalendar;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\M_Event_Calendar;
use Auth;

class SetupEventCalendarController extends Controller
{
    public function __construct() { 
        
    }

    public function index(){

        return view('backend.setup_event_calendar.setup-event-calendar');
    }

    public function storeInsertSetupCalendar(Request $request){
        
        $this->validate($request, [
            'event_startdate' => 'required|date|date_format:Y-m-d',
            'event_enddate' => 'required|date|date_format:Y-m-d',
            'event_name' => 'required|string|max:128|unique:tmii_ms_event_calendar,event_name',
            'event_type' => 'required|in:WEEK,HOLIDAY'
        ]);

        $request->merge([
            'cd_ms_event_calendar' => get_prefix('et_ordered_ticket_hd'),
            'created_by' => Auth::guard('administrator')->user()->id_us_backend_hd
        ]);

        M_Event_Calendar::create($request->all());

        return redirect()->back()->with('success', 'insert');
    }

    public function storeUpdateSetupCalendar(Request $request){

        if(!$request->exists('cd')){
            return redirect()->back()->with('failed', 'cd');
        }
        if($request->exists('event_name') && $request->exists('event_startdate') && $request->exists('event_enddate') && $request->exists('event_type') ){

            $validator = $this->validate($request, [
                'event_startdate' => 'required|date|date_format:Y-m-d',
                'event_enddate' => 'required|date|date_format:Y-m-d',
                'event_name' => 'required|string|max:128|unique:tmii_ms_event_calendar,event_name',
                'event_type' => 'required|in:WEEK,HOLIDAY'
            ]);
            
            if ($validator->fails()){
                return response()->json(['errors'=>$validator->errors()->all()]);
            }

            $request->merge([
                'updated_by' => Auth::guard('administrator')->user()->id_us_backend_hd,
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            $ddl = 'update';

        }else{
            
            $request->merge([
                'is_active' => 'N',
                'deleted_by' => Auth::guard('administrator')->user()->id_us_backend_hd,
                'deleted_at' => date('Y-m-d H:i:s')
            ]);

            $ddl = 'delete';

        }

        M_Event_Calendar::where('cd_ms_event_calendar', $request->cd)->update($request->except(['_token', 'cd']));

        return redirect()->back()->with('success', $ddl);
    }
}
