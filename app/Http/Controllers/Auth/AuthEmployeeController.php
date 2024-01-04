<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\EmployeeResource;
use App\Http\Traits\GeneralTrait;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Validator;

class AuthEmployeeController extends Controller
{
    use GeneralTrait;
    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'phone' => 'required|regex:/^09\d{8}$/',
                'password' => 'required|min:4|max:18',
            ]);
            if ($validator->fails()) {
                return $this->requiredField($validator->errors()->first());
            }
            $user = User::where('phone', $request->phone)->first();
            if (!$user || !Hash::check($request->password, $user->password)) {
                return $this->unAuthorizeResponse();
            }
            $data['token'] = $user->createToken('apiToken')->plainTextToken;
            $data['user'] = new EmployeeResource($user);
            return $this->apiResponse($data, true, null, 200);
        } catch (\Exception $ex) {
            return $this->apiResponse(null, false, $ex->getMessage(), 500);
        }
    }
    public function logout(Request $request)
    {
        try {
            $user = auth()->user()->tokens()->delete();
            $data['message'] = 'User has logged out successfully';
            return $this->apiResponse($data, true, null, 200);
        } catch (\Exception $ex) {
            return $this->apiResponse(null, false, $ex->getMessage(), 500);
        }
    }
}