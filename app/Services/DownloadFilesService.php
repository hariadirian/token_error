<?php
namespace App\Services;
  
class DownloadFilesService
{
    public function templateImage($type){
      $file= public_path(). "/adminbsb/images/template/".$type.".jpg";
      $response = response()->download($file);
      ob_end_clean();
      return $response;
    }
}