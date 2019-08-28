 <?php 
 
use App\Models\M_Code;

 if (! function_exists('indoDay')) {
     function indoDay($day = ''){
        if($day != ''){
            if(strtoupper($day) == 'SUN'){
                return 'Minggu';
            }elseif(strtoupper($day) == 'MON'){
                return 'Senin';
            }elseif(strtoupper($day) == 'TUE'){
                return 'Selasa';
            }elseif(strtoupper($day) == 'WED'){
                return 'Rabu';
            }elseif(strtoupper($day) == 'THU'){
                return 'Kamis';
            }elseif(strtoupper($day) == 'FRI'){
                return 'Jumat';
            }elseif(strtoupper($day) == 'SAT'){
                return 'Sabtu';
            }else{
                return 'Hari Tidak Ditemukan!';
            }
        }
     }
     
     function engDay($day = ''){
        if($day != ''){
            if(strtoupper($day) == 'MINGGU'){
                return 'SUN';
            }elseif(strtoupper($day) == 'SENIN'){
                return 'MON';
            }elseif(strtoupper($day) == 'SELASA'){
                return 'TUE';
            }elseif(strtoupper($day) == 'RABU'){
                return 'WED';
            }elseif(strtoupper($day) == 'KAMIS'){
                return 'THU';
            }elseif(strtoupper($day) == 'JUMAT'){
                return 'FRI';
            }elseif(strtoupper($day) == 'SABTU'){
                return 'SAT';
            }else{
                return 'Hari Tidak Ditemukan!';
            }
        }
     }
     
     function get_prefix($tbl = ''){
        $prefix_booking     = M_Code::where('tabletype', $tbl)
                                  ->select('prefix')
                                  ->get()
                                  ->first();
        return $prefix_booking->prefix.'-'.strtoupper(uniqid());
     }
     
     function get_min_discount($val1, $val2){
        if($val1 == 0 or $val1 == ''){
            return $val2;
        }
        if($val2 == 0 or $val2 == ''){
            return $val1;
        }
          return  ($val1 < $val2)? $val1 : $val2 ;
     }
     
     function get_max_discount($val1, $val2){
          return  ($val1 > $val2)? $val1 : $val2 ;
     }
}