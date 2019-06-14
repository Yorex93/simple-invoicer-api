<?php

namespace App\Http\Controllers\Api;

use App\Models\Company;
use App\Models\Profile;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SettingController extends Controller
{

	public  function index(Request $request){

	}


	public function update(Request $request){

	}


	public function updateProfile(Request $request){
		$validator = Validator::make($request->all(), [
			'companyName' => 'nullable|string',
			'companyAddress' => 'nullable|string',
			'companyLogo' => 'nullable|string',
			'userFullName' => 'nullable|string'
		]);

		if($validator->fails()){
			return response()->json(['error' => $validator->errors()], 401);
		}

		Profile::where('user_id', Auth::user()->id)->update([
			'full_name' => $request->userFullName
		]);

		Company::where('user_id', Auth::user()->id)->update([
			'company_name' => $request->companyName,
			'company_address' => $request->companyAddress,
			'company_logo' => $request->companyLogo
		]);
		$user = Auth::user()->load('profile', 'company');

		return response()->json(['success' => $user], 201);
	}
}
