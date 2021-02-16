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
        try {
            
            $municipalAccess = json_decode(Auth::user()->municipal_access);

            if ($municipalAccess[0] == "*") {
                return address::where('address', 'LIKE', "{$request->searched}%")->take(10)->get();
            }
            
            return address::whereIn('municipality', $municipalAccess)->where('address', 'LIKE', "{$request->searched}%")->get();


        } catch (Exception $e) {
            throw $e;
        }
    }

    public function saveAddress(Request $request){

    	$request->validate([
	    	'address' => 'required',
	    	'municipality' => 'required',
		]);

    	return address::create($request->all());
    }

    public function updateAddress(Request $request){
    	
    	$request->validate([
	    	'address' => 'required',
	    	'municipality' => 'required',
		]);

    	$add = address::findOrFail($request->address_id);
   		$add->address = $request->address;
   		$add->municipality = $request->municipality;
   		$add->save();

   		return $add;
    }
}
