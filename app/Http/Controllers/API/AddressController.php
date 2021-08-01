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
                return address::where('address', 'LIKE', "{$request->searched}%")
                        ->orWhere('municipality', 'LIKE', "{$request->searched}%")
                        ->take(10)
                        ->get();
            }
            
                return address::whereIn('municipality', $municipalAccess)
                        ->where('address', 'LIKE', "{$request->searched}%")
                        ->orWhere('municipality', 'LIKE', "{$request->searched}%")
                        ->get();


        } catch (Exception $e) {
            throw $e;
        }
    }

    public function storeAddress(Request $request){

    	$request->validate([
	    	'address' => 'required',
	    	'municipality' => 'required',
		]);

        $address = trim(preg_replace('/[^a-z]/i', '', $request->address));

        $result = address::whereRaw("REPLACE(`address`, ' ', '') = ? ", $address )->first();

        if ($result) {
            return response()->json(["message" => $request->address ." already exist"], 500);
        }

        return address::create([
            'address'=>  trim(preg_replace('/[^a-z\s.-d]/i', '',$request->address)),
            'municipality' => $request->municipality,
        ]);

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
