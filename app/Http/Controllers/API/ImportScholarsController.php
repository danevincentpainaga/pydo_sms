<?php

namespace App\Http\Controllers\API;

// set_time_limit(0);

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\scholar;
use App\Models\address;
use App\Models\course;
use DB;

class ImportScholarsController extends Controller
{

	public function getScholarsWithCount(Request $request){
		try {
			DB::beginTransaction();
			$count = scholar::where('degree', $request->degree)
						->where('contract_status', '!=', 'In-Active')
						->count();
			$scholars = scholar::where('degree', $request->degree)
						->where('contract_status', '!=', 'In-Active')
						->skip((int)$request->page * 2)
						->take(2)
						->get();
			DB::commit();
		 	return response()->json(['scholars'=> $scholars, 'count'=> $count ], 200);
		} catch (Exception $e) {
			DB::rollback();
			throw new Exception($e);
		}
	}

	public function importScholars(Request $request)
	{

		try {

		    $request->validate([
		        'scholars' => 'required',
		        'error' => 'required',
		    ]);

		    if ($request->error > 0) {
		    	return response()->json(['message'=> 'Imported data has '. $request->error ." errors"], 422);	
		    }

			// $imported_scholars = [];

			foreach ($request->scholars as $scholars => $scholar) {

				$scholar['father_details'] = json_encode($scholar['father_details']);
				$scholar['mother_details'] = json_encode($scholar['mother_details']);

				$scholar['contract_status'] = 'Pre-Approved';
				$scholar['last_renewed'] = $scholar['contract_id'];
				$scholar['sem_year_applied'] = $scholar['contract_id'];
				$scholar['created_at'] = now()->toDateTimeString();
				$scholar['updated_at'] = now()->toDateTimeString();
				$scholar['userId'] = Auth::id();

				// $imported_scholars[] = $scholar;
			}

			DB::disableQueryLog();

			$chunks = array_chunk($request->scholars, 2000);

			DB::beginTransaction();

			foreach ($chunks as $chunk) {
				scholar::insert($chunk);
			}

			DB::commit();

			return $chunks;


		} catch (Exception $e) {
			DB::roolback();
			throw $e;
		}

	}

	public function getAllScholars(Request $request){
		$result = [];
		$query = DB::table('scholars');
		$query->where('degree', $request->degree);
		$query->whereIn('contract_status', ['Approved', 'Pre-Approved', 'Pending']);
		$query->orderBy('scholar_id')->chunk(25000, function ($scholars) use (&$result){
			foreach ($scholars as $scholar) {
				$result[] = $scholar;
			}
		});
		return $result;
	}

    public function getAddresses(Request $request){
        try {

            return address::where('municipality', $request->municipality)->get();


        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getCourses(Request $request){
        return course::where('course_degree', $request->degree)->get();
    }

}
