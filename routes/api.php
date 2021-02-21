<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\SchoolController;
use App\Http\Controllers\API\AddressController;
use App\Http\Controllers\API\ScholarController;
use App\Http\Controllers\API\ScholarParentsController;
use App\Http\Controllers\API\AcademicContractController;
use App\Http\Controllers\API\UserAccountsController;
use App\Http\Controllers\API\MunicipalitiesController;
use App\Http\Controllers\API\AcademicSemesterYearContractController;
use App\Http\Controllers\API\ExportScholarsController;
use App\Http\Controllers\API\ImportScholarsController;
use App\Http\Controllers\API\dashboardController;
use App\Http\Controllers\API\CourseController;


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

	// Route::get('getAllScholars', [ScholarController::class, 'getAllScholars']);
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

	Route::post('saveCourse', [CourseController::class, 'saveCourse']);
	Route::post('updateCourse', [CourseController::class, 'updateCourse']);
	Route::get('getCourses', [CourseController::class, 'getCourses']);


	Route::get('getListOfSchool', [SchoolController::class, 'getListOfSchool']);
	Route::post('saveSchoolDetails', [SchoolController::class, 'saveSchoolDetails']);
	Route::post('updateSchoolDetails', [SchoolController::class, 'updateSchoolDetails']);
	

	Route::post('getUserAccounts', [UserAccountsController::class, 'getUserAccounts'])->middleware('admin');
	
	
	Route::get('getAcademicContractDetails', [AcademicContractController::class, 'getAcademicContractDetails']);
	Route::get('getAcademicYearList', [AcademicSemesterYearContractController::class, 'getAcademicYearList']);
	Route::post('saveAcademicYearList', [AcademicSemesterYearContractController::class, 'saveAcademicYearList'])->middleware('admin');
	Route::post('updateAcademicYearList', [AcademicSemesterYearContractController::class, 'updateAcademicYearList'])->middleware('admin');


	Route::post('setContract', [AcademicContractController::class, 'setContract'])->middleware('admin');
	Route::post('closeContract', [AcademicContractController::class, 'closeContract'])->middleware('admin');
	Route::post('openContract', [AcademicContractController::class, 'openContract'])->middleware('admin');
	Route::post('confirmPassword', [AcademicContractController::class, 'confirmPassword'])->middleware('admin');
	

	Route::get('getDegrees', function(){
		return Auth::user()->degree_access;
	});


	Route::get('getScholarsToExport', [ExportScholarsController::class, 'getScholarsToExport']);
	Route::post('importScholars', [ImportScholarsController::class, 'importScholars']);
	Route::get('getAllScholars', [ImportScholarsController::class, 'getAllScholars']);
	Route::get('import/getAddresses', [ImportScholarsController::class, 'getAddresses']);

	Route::get('getAuthenticatedUser', [AuthController::class, 'getAuthenticatedUser']);
	Route::post('logout', [AuthController::class, 'logout']);


	Route::get('undergraduateScholarsCount', [dashboardController::class, 'undergraduateScholarsCount']);
	Route::get('mastersScholarsCount', [dashboardController::class, 'mastersScholarsCount']);
	Route::get('getApprovedScholarsCount', [dashboardController::class, 'getApprovedScholarsCount']);
	Route::get('getNewOldTotalPerDegree', [dashboardController::class, 'getNewOldTotalPerDegree']);
	Route::get('getContractStatusTotalPerDegree', [dashboardController::class, 'getContractStatusTotalPerDegree']);
	
});
