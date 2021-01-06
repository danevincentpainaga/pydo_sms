<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
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
	        throw ValidationException::withMessages([
	            'email' => ['The provided credentials are incorrect.'],
	        ]);
	    }

	    $success = $user->createToken('pydo_appKey')->plainTextToken;
		return response()->json(
		 							[
		 								'success'=>$success
		 							], 
		 							200
		 						);
    }
}
