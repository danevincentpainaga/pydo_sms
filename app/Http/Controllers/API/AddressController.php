<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\address;
use DB;

class AddressController extends Controller
{
    public function getAddresses(Request $request){
		return DB::table('addresses')
	            ->where(DB::raw('CONCAT(barangay_name," ",municipality," ANTIQUE")'), 'like', "{$request->searched}%")
	            ->select('addresses.address_id', DB::raw('CONCAT(barangay_name," ",municipality,", ANTIQUE") as full_address') )
	            ->get(); 
    }
}
