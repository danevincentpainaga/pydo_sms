<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\scholar;
use DB;

class ImportScholarsController extends Controller
{

	public function importScholars(Request $request)
	{

		try {

			$imported_scholars = [];

			foreach ($request->all() as $scholars => $scholar) {

				$scholar['father_details'] = json_encode($scholar['father_details']);
				$scholar['mother_details'] = json_encode($scholar['mother_details']);

				$scholar['contract_status'] = 'Pre-Approved';
				$scholar['last_renewed'] = $scholar['contract_id'];
				$scholar['sem_year_applied'] = $scholar['contract_id'];
				$scholar['userId'] = Auth::id();

				$imported_scholars[] = $scholar; 
			}

			$chunks = array_chunk($imported_scholars, 1000);

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

}
