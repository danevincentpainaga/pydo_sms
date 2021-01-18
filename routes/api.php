<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\SchoolController;
use App\Http\Controllers\API\AddressController;
use App\Http\Controllers\API\ScholarController;
use App\Http\Controllers\API\ScholarParentsController;
use App\Http\Controllers\API\AccademicContractController;
use App\Http\Controllers\API\ProvinceController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::middleware('auth:sanctum')->group(function () {

	Route::post('getProvinces', [ProvinceController::class, 'getProvinces']);
	Route::get('getSearchedSchool/{searched}', [SchoolController::class, 'getSearchedSchool']);
	Route::post('getListOfSchool', [SchoolController::class, 'getListOfSchool']);
	Route::post('getAddresses', [AddressController::class, 'getAddresses']);

	Route::post('saveScholar', [ScholarController::class, 'saveScholar']);

	Route::post('getMotherList', [ScholarParentsController::class, 'getMotherList']);
	Route::post('getFatherList', [ScholarParentsController::class, 'getFatherList']);

	Route::get('getAcademicContractDetails', [AccademicContractController::class, 'getAcademicContractDetails']);
	Route::post('getNewScholars', [ScholarController::class, 'getNewScholars']);
	Route::post('getScholars', [ScholarController::class, 'getScholars']);
	Route::post('saveSchoolDetails', [SchoolController::class, 'saveSchoolDetails']);
	Route::post('updateSchoolDetails', [SchoolController::class, 'updateSchoolDetails']);
	
});

Route::post('login', [AuthController::class, 'login']);