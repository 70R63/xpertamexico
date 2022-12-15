<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\API\ApiController;
use Carbon\Carbon;

class LoginController extends ApiController
{
    public function login(Request $request)
    {
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])) { 
            $user = Auth::user(); 

            $response['token'] = $user->createToken($request->email,array(),Carbon::now()->addMinutes(1439))->plainTextToken; 
            $response['name'] =  $user->name;
   
            return $this->successResponse('User successfully logged-in.', $response);
        } 
        else { 
            return $this->sendError('Unauthorized.', ['error'=>'Unauthorized'], 403);
        } 
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'confirm_password' => 'required|same:password',
           
        ]);
   
        if($validator->fails()){
        	return $this->sendError('Validation error.', $validator->errors(), 400);
        }
   
        $data = $request->all();
        $data['password'] = bcrypt($data['password']);
        $data['empresa_id']=1;
        $user = User::create($data);

        $response['token'] =  $user->createToken($request->email)->plainTextToken;
        $response['name'] =  $user->name;
   
        return $this->successResponse('User created successfully.', $response);
    }

    public function logout() 
    {
        auth()->user()->currentAccessToken()->delete();

        return $this->successResponse('Logout successfully.');
    }
}
