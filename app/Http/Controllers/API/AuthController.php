<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request){
	    $request->validate([
	        'email' => 'required|email',
	        'password' => 'required',
	    ]);

	    $user = User::where('email', $request->email)->first();

	    if (! $user || ! Hash::check($request->password, $user->password)) {
	        return response()->json(['error' => 'The provided credentials are incorrect.'], 401);
	    }

	    $success = $user->createToken('pydo_appKey')->plainTextToken;

		return response()->json(['success'=>$success], 200);
    }

    public function getAuthenticatedUser(){
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
}
