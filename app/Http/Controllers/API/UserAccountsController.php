<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\ValidateUserDetails;
use App\Models\User;

class UserAccountsController extends Controller
{

    public function getUserAccounts(Request $request){
        try {
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
        } catch (\Throwable $ex) {
            \Log::error($ex);
            throw $ex;
        }
    }

    public function createUsersAccount(ValidateUserDetails $request) {
        try {
            // $validator = Validator::make($request->all(), [
            //     'name' => 'required|string|max:255',
            //     'email' => 'required|string|email|unique:users',
            //     'password' => 'required|string|min:3',
            //     'municipal_access' => 'required|array|min:1',
            //     'degree_access' => 'required|array|min:1',
            //     'user_type' => 'required|string',
            //     'status' => 'required|integer|min:1|max:1|digits_between:0,1',
            // ]);

            // if ($validator->fails()) {
            //     return response('Invalid inputs', 400);
            // }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'municipal_access' => $request->municipal_access,
                'degree_access' => $request->degree_access,
                'user_type' => $request->user_type,
                'status' => $request->status
            ]);
    
            return response()->json(['success'=> 'User created'], 200);
        } catch (\Throwable $ex) {
            \Log::error($ex);
            throw $ex;
        }
    }

}
