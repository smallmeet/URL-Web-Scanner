<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserDirController extends Controller
{
    //

    public function create_user_dir(){
    	if(is_dir(base_path('wpscan'))){
    		echo "True";
    	}else{
    		echo "False";
    	}
    }
}
