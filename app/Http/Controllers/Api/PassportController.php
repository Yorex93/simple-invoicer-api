<?php

namespace App\Http\Controllers\Api;

use App\Models\Company;
use App\Models\Profile;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PassportController extends Controller
{
	public $successStatus = 200;
	/*
	 * Login Function
	 * @param \Illuminate\Http\Request
	 * @return json response
	 */
	public function login(Request $request){
//		for($i = 0; $i < 50000000000; $i++){
//			$a = 1;
//		}
		if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
			$user = Auth::user();
			$success['token'] = $user->createToken('Invoicer')->accessToken;
			return response()->json(['success' => $success], $this->successStatus);
		}else{
			return response()->json(['error' => 'Unauthorized'], 401);
		}
	}


		/*
		 * Login Function
		 * @param \Illuminate\Http\Request
		 * @return json response
		 */
	public function register(Request $request){
		$validator = Validator::make($request->all(), [
			'name' => 'required',
			'email' => 'required|email|unique:users',
			'password' => 'required|string|min:6',
			'confirm_password' => 'required|same:password',
		]);

		if($validator->fails()){
			return response()->json(['error' => $validator->errors()], 401);
		}

		$input = $request->all();
		$input['password'] = bcrypt($input['password']);
		$user = User::create([
			'name' => $input['name'],
			'email' => $input['email'],
			'password' => bcrypt($input['password']),
		]);

		$profile = Profile::create([
			'user_id' => $user->id,

		]);

		$company = Company::create([
			'user_id' => $user->id
		]);

		$success['token'] = $user->createToken('Invoicer')->accessToken;
		$success['name'] = $user->name;

		return response()->json(['success' => $success], $this->successStatus);
	}


	/*
	 * Return User details
	 * @param \Illuminate\Http\Request
	 */
	public function getDetails(Request $request){
		$user = Auth::user()->load('profile', 'company');
		return response()->json(['success' => $user], $this->successStatus);
	}
}
