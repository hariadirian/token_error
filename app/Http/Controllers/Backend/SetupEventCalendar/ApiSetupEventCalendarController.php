<?php

namespace App\Http\Controllers\Backend\SetupEventCalendar;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\M_Event_Calendar;
use DB;

class ApiSetupEventCalendarController extends Controller
{
    public function __construct() { 
        
    }

    public function apiGetEventCalendar(){

        return M_Event_Calendar::where('state', 'Y')
                ->where('is_active', 'Y')
                ->select('event_name as title', 
                    'event_startdate as start', 
                    'cd_ms_event_calendar', 
                    'event_enddate as end', 
                    DB::raw('(CASE WHEN event_type=\'HOLIDAY\' THEN \'red\'  ELSE \'blue\' END) as color'
                 ))->get()->toJson();

    }

    public function apiGetModalEventCalendar($start, $end){

        if($start AND $end){
            $data = [
                'start' => $start,
                'end' => $end,
            ];

            $modal_content = preg_replace( "/\r|\n/", "", view('backend.setup_event_calendar.modal-setup-event-calendar', $data) );

            return json_encode(array($modal_content));
        }else{
            return 'Data tidak punya parameter';
        }
    }
}
