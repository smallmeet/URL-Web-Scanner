<?php
namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Http\Controllers\UserDirController;
use App\Http\Controllers\Auth\RegisteredController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Log;




class CurlController extends Controller
{


    
     
    public function curl_request($url){

        @set_time_limit ( 80 );

         $options = array(
            
        CURLOPT_RETURNTRANSFER => true,   // return web page
        CURLOPT_HEADER         => false,  // don't return headers
        CURLOPT_FOLLOWLOCATION => true,   // follow redirects
        CURLOPT_MAXREDIRS      => 10,     // stop after 10 redirects
        CURLOPT_ENCODING       => "",     // handle compressed
        CURLOPT_USERAGENT      => "test", // name of client
        CURLOPT_AUTOREFERER    => true,   // set referrer on redirect
        CURLOPT_CONNECTTIMEOUT => 120,    // time-out on connect
        CURLOPT_TIMEOUT        => 500    // time-out on response
           );

        $ch = curl_init($url);
        curl_setopt_array($ch, $options);

        $content  = curl_exec($ch);
        $errors = curl_error($ch);
        $response = curl_getinfo($ch, CURLINFO_HTTP_CODE);


       

       
        if($errors){
            Log::info($errors);
            die("Error With Curl Request");
        }else if($response===200){
          return $content;
        }

         curl_close($ch);
      
    }

}
