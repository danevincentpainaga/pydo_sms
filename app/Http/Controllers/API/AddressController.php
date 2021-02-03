<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\address;
use DB;

class AddressController extends Controller
{
    public function getAddresses(Request $request){

    	$municipalAccess = json_decode(Auth::user()->municipal_access);

    	if ($municipalAccess[0] == "*") {
    		return address::where('address', 'LIKE', "{$request->searched}%")->get();
    	}
    	
	    return address::whereIn('municipality', $municipalAccess)->where('address', 'LIKE', "{$request->searched}%")->get();
    }
}
