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
	            ->join('municipalities', 'municipalities.municipality_id', '=', 'addresses.municipalityId')
	            ->join('provinces', 'provinces.province_id', '=', 'addresses.a_province_id')
	            ->where(DB::raw('CONCAT(barangay_name," ",municipality," ",province_name)'), 'like', "%{$request->searched}%")
	            ->select('addresses.address_id', 'addresses.barangay_name', 'provinces.province_name', 'municipalities.municipality', DB::raw('CONCAT(barangay_name," ",municipality,", ",province_name) as full_address') )
	            ->get(); 
    }
}
