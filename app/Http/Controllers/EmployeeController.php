<?php

namespace App\Http\Controllers;

use App\Http\Resources\Employee2Resource;
use App\Http\Resources\EmployeeResource;
use App\Http\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Validator;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Str;
use App\Http\Resources\EmployeeCollection;


class EmployeeController extends Controller
{
    use GeneralTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $employees = User::whereHas('roles', function ($querey) {
                $querey->whereIn('name', ['nurse', 'doctor']);
            })->with('roles')->get();
            $data['employees'] = EmployeeResource::collection($employees);
            return $this->apiResponse($data, true, null);
        } catch (\Exception $ex) {
            return $this->apiResponse(null, false, $ex->getMessage(), 500);
        }
    }
    public function show($uuid)
    {
        try {
            $user_uuid = auth()->user()->uuid;
            if ($user_uuid == $uuid || auth()->user()->roles()->where('name', 'admin')->first()) {
                $employee = User::whereHas('roles', function ($querey) {
                    $querey->whereIn('name', ['nurse', 'doctor', 'admin']);
                })->with('roles')->where('uuid', $uuid)->first();
                $data['employee'] = new EmployeeResource($employee);
                return $this->apiResponse($data, true, null);
            }
            return $this->notFoundResponse('Employee not found');
        } catch (\Exception $ex) {
            return $this->apiResponse(null, false, $ex->getMessage(), 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'first_name' => 'required|min:4|max:18',
                'last_name' => 'required|min:4|max:18',
                'phone' => 'required|regex:/^09\d{8}$/|unique:users',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:4|max:18',
                'birth' => 'required',
                'salary' => 'required',
                'role_uuid' => 'required',
            ]);
            if ($validator->fails()) {
                return $this->requiredField($validator->errors()->first());
            }
            $birthDate = Carbon::parse($request->birth);
            $currentDate = Carbon::now();
            $age = $currentDate->diffInYears($birthDate);
            $employee = User::create([
                'uuid' => Str::uuid(),
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'phone' => $request->phone,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'birth' => $request->birth,
                'age' => $age,
                'salary' => $request->salary,
            ]);
            $role = Role::where('uuid', $request->role_uuid)->first();
            if (!$role)
                return $this->notFoundResponse("The role does not exist");
            $employee->roles()->attach($role);
            $data['Employee'] = new EmployeeResource($employee);
            return $this->apiResponse($data, true, null, 200);
        } catch (\Exception $ex) {
            return $this->apiResponse(null, false, $ex->getMessage(), 500);
        }
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $uuid)
    {
        try {
            $employee = User::whereHas('roles', function ($querey) {
                $querey->whereIn('name', ['nurse', 'doctor', 'admin']);
            })->with('roles')->where('uuid', $uuid)->first();
            if ($employee) {
                $employee->first_name = $request->first_name;
                $employee->last_name = $request->last_name;
                $employee->phone = $request->phone;
                $employee->email = $request->email;
                $employee->password = $request->password;
                $employee->birth = $request->birth;
                $birthDate = Carbon::parse($request->birth);
                $currentDate = Carbon::now();
                $age = $currentDate->diffInYears($birthDate);
                $employee->age = $age;
                $employee->salary = $request->salary;
                $role = Role::where('uuid', $request->role_uuid)->first();
                if (!$role)
                    return $this->notFoundResponse("The role does not exist");
                $employee->roles()->sync($role->id);
                $employee->update();
                $data['employee'] = new EmployeeResource($employee);
                return $this->apiResponse($data, true, null, 200);
            }
            return $this->notFoundResponse("Employee not found");
        } catch (\Exception $ex) {
            return $this->apiResponse(null, false, $ex->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}