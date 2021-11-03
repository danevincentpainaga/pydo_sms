<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class UserAccountsController extends Controller
{

    public function getUserAccounts(Request $request){
        if (Auth::user()->user_type == 'Admin') {
        return User::where('user_type', 'User')
                    ->where('name', 'LIKE', "{$request->searched}%")
                    ->orWhere('id', Auth::id())
                    ->where('name', 'LIKE', "{$request->searched}%")
                    ->where('user_type', '=', "Admin")
                    ->get();
        }
    	return User::whereIn('user_type', ['Admin', 'User'])
                    ->where('name', 'LIKE', "{$request->searched}%")
                    ->orWhere('id', Auth::id())
                    ->where('name', 'LIKE', "{$request->searched}%")
                    ->where('user_type', '=', "SuperAdmin")
                    ->get();
    }

    public function createUsersAccount(Request $request){

        $attr = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:3',
            'municipal_access' => 'required',
            'degree_access' => 'required',
            'user_type' => 'required',
            'status' => 'required',
        ]);

        $user = User::create([
            'name' => $attr['name'],
            'email' => $attr['email'],
            'password' => bcrypt($attr['password']),
            'municipal_access' => json_encode($attr['municipal_access']),
            'degree_access' => json_encode($attr['degree_access']),
            'user_type' => $attr['user_type'],
            'status' => $attr['status']
        ]);

        return response()->json(['success'=> 'User created'], 200);
    }

}
