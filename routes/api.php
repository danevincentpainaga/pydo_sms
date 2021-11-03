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
use App\Http\Controllers\API\GovernorController;

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
Route::post('createUserAccounts', [UserAccountsController::class, 'createUserAccounts']);

Route::get('login',function(){
	return abort(404);
});

Route::middleware('auth:sanctum')->group(function () {

	Route::group(['prefix' => 'scholars'], function () {

		Route::get('getScholars', [ScholarController::class, 'getScholars']);
		Route::post('storeNewScholarDetails', [ScholarController::class, 'storeNewScholarDetails']);
		Route::post('updateScholarDetails', [ScholarController::class, 'updateScholarDetails']);
		Route::post('uploadProfilePic', [ScholarController::class, 'uploadProfilePic']);
		Route::get('getNewUndergraduateScholars', [ScholarController::class, 'getNewUndergraduateScholars']);
		Route::get('getNewMastersDoctorateScholars', [ScholarController::class, 'getNewMastersDoctorateScholars']);
		
	});

	Route::group(['prefix' => 'parents'], function () {

		Route::get('getMotherList', [ScholarParentsController::class, 'getMotherList']);
		Route::get('getFatherList', [ScholarParentsController::class, 'getFatherList']);
		Route::post('updateScholarParentsDetails', [ScholarParentsController::class, 'updateScholarParentsDetails']);

	});

	Route::group(['prefix' => 'address'], function () {

		Route::post('storeAddress', [AddressController::class, 'storeAddress']);
		Route::post('updateAddress', [AddressController::class, 'updateAddress']);
		Route::get('getAddresses', [AddressController::class, 'getAddresses']);
		
	});

	Route::group(['prefix' => 'course'], function () {

		Route::post('storeCourse', [CourseController::class, 'storeCourse']);
		Route::post('updateCourse', [CourseController::class, 'updateCourse']);
		Route::get('getCourses', [CourseController::class, 'getCourses']);
		Route::get('getCoursesList', [CourseController::class, 'getCoursesList']);
		
	});

	Route::group(['prefix' => 'school'], function () {

		Route::get('getListOfSchool', [SchoolController::class, 'getListOfSchool']);
		Route::post('storeSchoolDetails', [SchoolController::class, 'storeSchoolDetails']);
		Route::post('updateSchoolDetails', [SchoolController::class, 'updateSchoolDetails']);
		
	});


	Route::group(['prefix' => 'academic'], function () {

		Route::get('getAcademicYearList', [AcademicSemesterYearContractController::class, 'getAcademicYearList']);
		Route::post('storeAcademicYearList', [AcademicSemesterYearContractController::class, 'storeAcademicYearList'])->middleware('admin_access');
		Route::post('updateAcademicYearList', [AcademicSemesterYearContractController::class, 'updateAcademicYearList'])->middleware('admin_access');
		
	});


	Route::group(['prefix' => 'contract'], function () {

		Route::get('getAcademicContractDetails', [AcademicContractController::class, 'getAcademicContractDetails']);
		Route::post('setContract', [AcademicContractController::class, 'setContract'])->middleware('admin_access');
		Route::post('closeContract', [AcademicContractController::class, 'closeContract'])->middleware('admin_access');
		Route::post('openContract', [AcademicContractController::class, 'openContract'])->middleware('admin_access');
		Route::post('confirmPassword', [AcademicContractController::class, 'confirmPassword'])->middleware('admin_access');
			
	});

	Route::group(['prefix' => 'import'], function () {

		Route::post('importScholars', [ImportScholarsController::class, 'importScholars']);
		Route::get('getAllScholars', [ImportScholarsController::class, 'getAllScholars']);
		Route::get('getAddresses', [ImportScholarsController::class, 'getAddresses']);
		Route::get('getCourses', [ImportScholarsController::class, 'getCourses']);
		
	});


	Route::group(['prefix' => 'dashboard'], function () {

		Route::get('undergraduateScholarsCount', [dashboardController::class, 'undergraduateScholarsCount']);
		Route::get('mastersScholarsCount', [dashboardController::class, 'mastersScholarsCount']);
		Route::get('getApprovedScholarsCount', [dashboardController::class, 'getApprovedScholarsCount']);
		Route::get('getNewOldTotalPerDegree', [dashboardController::class, 'getNewOldTotalPerDegree']);
		Route::get('getContractStatusTotalPerDegree', [dashboardController::class, 'getContractStatusTotalPerDegree']);
		
	});

	Route::group(['prefix' => 'governor', 'middleware' => 'superadmin'], function () {

		Route::post('updateGovernor', [GovernorController::class, 'updateGovernor']);
		Route::get('getGovernorDetails', [GovernorController::class, 'getGovernorDetails']);

	});

	Route::group(['prefix' => 'users', 'middleware' => 'admin_access'], function () {

		Route::get('getUserAccounts', [UserAccountsController::class, 'getUserAccounts']);
		Route::post('createUsersAccount', [UserAccountsController::class, 'createUsersAccount']);

	});


	Route::get('export/getScholarsToExport', [ExportScholarsController::class, 'getScholarsToExport']);

	Route::get('getMunicipalities', [MunicipalitiesController::class, 'getMunicipalities']);
	
	
	Route::get('getAuthenticatedUser', [AuthController::class, 'getAuthenticatedUser']);
	Route::post('confirmIsAdminAccess', [AuthController::class, 'isAdminAccess']);
	Route::post('logout', [AuthController::class, 'logout']);
	
	
	Route::get('getDegrees', function(){
		return Auth::user()->degree_access;
	});

});
