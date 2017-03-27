<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\CurlController;
use App\Http\Controllers\UserDirController;
use App\Http\Controllers\Auth\RegisteredController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Log;




class ScanSecurityScanController extends Controller
{


    
     
    public function update(Request $request){

          $curl = new CurlController;
    	    $user_name = json_decode(Auth::user());
    	    $user_dir =  base_path("users\\") . $user_name->email;
    	    $filesystem = new Filesystem;
          //Log::info("Error in executing process");
   



    }



  


}
