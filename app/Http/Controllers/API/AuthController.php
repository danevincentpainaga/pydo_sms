<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use DB;

class AuthController extends Controller
{
    public function login(Request $request)
    {
		try {
		    DB::connection()->getPdo();
		} catch (\Exception $e) {
		    return response()->json(['error' => 'No database connection'], 500);
		}
    
	    $request->validate([
	        'email' => 'required|email',
	        'password' => 'required',
	    ]);

	    $user = User::where('email', $request->email)->first();

	    if (! $user || ! Hash::check($request->password, $user->password)) {
	        return response()->json(['error' => 'The provided credentials are incorrect.'], 401);
	    }

	    $success = $user->createToken('pydo_appKey')->plainTextToken;

		return response()->json(['token'=>$success], 200);
    }

    public function getAuthenticatedUser(){
		try {
		    DB::connection()->getPdo();
		} catch (\Exception $e) {
		    return response()->json(['error' => 'Connection lost'], 500);
		}
    
    	return Auth::user();
    }

    public function logout(Request $request){
    	return $request->user()->currentAccessToken()->delete();
    }

    public function isAdminAccess(Request $request){
	    if (!Hash::check($request->password, Auth::user()->password)) {
			return response()->json(['message'=> 'Incorrect password'], 403 );
	    }

		return true;
    }

    public function validateSupervisorCredentials(Request $request){

    	try {

		    $request->validate([
		        'email' => 'required|email',
		        'password' => 'required',
		    ]);

		    $user = User::where(['email' => $request->email, 'user_type' => 'Supervisor'])->first();

		    if (! $user || ! Hash::check($request->password, $user->password)) {
		        return response()->json(['error' => 'Incorrect email or password'], 401);
		    }

			return response()->json(['message'=> 'Success'], 200);

    	} catch (Exception $e) {
    		throw $e;
    	}
    }
}
