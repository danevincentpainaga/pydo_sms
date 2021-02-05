<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\SchoolController;
use App\Http\Controllers\API\AddressController;
use App\Http\Controllers\API\ScholarController;
use App\Http\Controllers\API\ScholarParentsController;
use App\Http\Controllers\API\AccademicContractController;
use App\Http\Controllers\API\UserAccountsController;
use App\Http\Controllers\API\MunicipalitiesController;
use App\Http\Controllers\API\AccademiSemesterYearcContractController;
use App\Http\Controllers\API\ExportScholarsController;
use App\Http\Controllers\API\dashboardController;



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

Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

	Route::get('getScholars', [ScholarController::class, 'getScholars']);
	Route::post('saveNewScholarDetails', [ScholarController::class, 'saveNewScholarDetails']);
	Route::post('updateScholarDetails', [ScholarController::class, 'updateScholarDetails']);
	Route::post('uploadProfilePic', [ScholarController::class, 'uploadProfilePic']);
	

	Route::get('getNewUndergraduateScholars', [ScholarController::class, 'getNewUndergraduateScholars']);
	Route::get('getNewMastersDoctorateScholars', [ScholarController::class, 'getNewMastersDoctorateScholars']);


	Route::get('getMotherList', [ScholarParentsController::class, 'getMotherList']);
	Route::get('getFatherList', [ScholarParentsController::class, 'getFatherList']);
	Route::post('updateScholarParentsDetails', [ScholarParentsController::class, 'updateScholarParentsDetails']);

	
	Route::post('saveAddress', [AddressController::class, 'saveAddress']);
	Route::post('updateAddress', [AddressController::class, 'updateAddress']);
	Route::get('getAddresses', [AddressController::class, 'getAddresses']);
	Route::get('getMunicipalities', [MunicipalitiesController::class, 'getMunicipalities']);


	// Route::get('getSearchedSchool/{searched}', [SchoolController::class, 'getSearchedSchool']);
	Route::get('getListOfSchool', [SchoolController::class, 'getListOfSchool']);
	Route::post('saveSchoolDetails', [SchoolController::class, 'saveSchoolDetails']);
	Route::post('updateSchoolDetails', [SchoolController::class, 'updateSchoolDetails']);
	

	Route::post('getUserAccounts', [UserAccountsController::class, 'getUserAccounts'])->middleware('admin');
	
	
	Route::get('getAcademicContractDetails', [AccademicContractController::class, 'getAcademicContractDetails']);
	Route::get('getAcademicYearList', [AccademiSemesterYearcContractController::class, 'getAcademicYearList']);
	Route::post('saveAcademicYearList', [AccademiSemesterYearcContractController::class, 'saveAcademicYearList'])->middleware('admin');
	Route::post('updateAcademicYearList', [AccademiSemesterYearcContractController::class, 'updateAcademicYearList'])->middleware('admin');


	Route::post('setContract', [AccademicContractController::class, 'setContract'])->middleware('admin');
	Route::post('closeContract', [AccademicContractController::class, 'closeContract'])->middleware('admin');
	Route::post('openContract', [AccademicContractController::class, 'openContract'])->middleware('admin');
	Route::post('confirmPassword', [AccademicContractController::class, 'confirmPassword'])->middleware('admin');
	

	Route::get('getDegrees', function(){
		return Auth::user()->degree_access;
	});


	Route::get('getScholarsToExport', [ExportScholarsController::class, 'getScholarsToExport']);


	Route::get('getAuthenticatedUser', [AuthController::class, 'getAuthenticatedUser']);
	Route::post('logout', [AuthController::class, 'logout']);


	Route::get('newScholarsCount', [dashboardController::class, 'newScholarsCount']);
	Route::get('oldScholarsCount', [dashboardController::class, 'oldScholarsCount']);
	
});
