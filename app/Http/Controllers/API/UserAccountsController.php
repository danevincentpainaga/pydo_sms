<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class UserAccountsController extends Controller
{

    public function getUserAccounts(Request $request){
    	return User::where('name', 'LIKE', "{$request->searched}%")->get();
    }

}
