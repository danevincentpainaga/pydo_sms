<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\scholar;
use App\Models\address;
use DB;

class dashboardController extends Controller
{

	public function undergraduateScholarsCount()
	{

		return $this->getSholarsPerMunicipalityCount(["Undergraduate"]);

	}

	public function mastersScholarsCount()
	{

		return $this->getSholarsPerMunicipalityCount(["Masters", "Doctorate"]);

	}

	private function getSholarsPerMunicipalityCount($degree)
	{

		$scholarsCount = [
			'municipalities' => [],
			'scholars_count' => [[],[],]
		];

		$municipalities = json_decode(Storage::get('municipalities/municipalities.json'));

		foreach ($municipalities as $key => $value) {

		 	$new = DB::table('scholars')
			->join('addresses', 'addresses.address_id', '=', 'scholars.addressId')
			->where('addresses.municipality', $value->municipality)
			->whereIn('degree', $degree)
			->whereIn('contract_status', ['Approved', 'Pre-Approved'])
			->where('scholar_status', 'NEW')
			->count();

		 	$old = DB::table('scholars')
			->join('addresses', 'addresses.address_id', '=', 'scholars.addressId')
			->where('addresses.municipality', $value->municipality)
			->whereIn('degree', $degree)
			->whereIn('contract_status', ['Approved', 'Pre-Approved'])
			->where('scholar_status', 'OLD')
			->count();

			$scholarsCount['municipalities'][] = $value->municipality;
			$scholarsCount['scholars_count'][0][] = $new;
			$scholarsCount['scholars_count'][1][] = $old;

		}

		return $scholarsCount;
	}

	public function getApprovedScholarsCount()
	{
		return $this->approvedRecursionQuery(['undergraduate', 'Masters', 'Doctorate'], [], 0);
	}

	private function approvedRecursionQuery($degreeArray, $returnedArray, $count)
	{
	 	$data = DB::table('scholars')
				->where('degree', $degreeArray[$count])
				->where('contract_status', 'Pre-Approved')
				->count();

		array_push($returnedArray, $data);

		if ($count >= 2) {
			return response()->json(['undergraduate' => $returnedArray[0], 'masters' => $returnedArray[1], 'doctorate' => $returnedArray[2] ], 200);
		}

		$count += 1;

		return $this->approvedRecursionQuery($degreeArray, $returnedArray, $count);
	}

}
