<?php

namespace App\Http\Controllers\App;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Validator;

class AuthController extends BaseController
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name'=>['required','string','max:150'],
            'usernme'=>['required','string','max:150','unique:users'],
            'email'=>['required','string','email','unique:users'],
            'mobile'=>['required','numeric','unique:users'],
            'password'=>['required','string','min:8'],
            'c_password'=>'required|same:password'
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validate Error',$validator->errors() );
        }

        $input = $request->all();
        $input['password'] = Hash::make($input['password']);
        $user = User::create($input);
        $success['token'] = $user->createToken('@123*EMOOO*457##')->accessToken;
        $success['name'] = $user->name;
        return $this->sendResponse($success, 'User registered Successfully!' );
    }



    public function login(Request $request)
    {


        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            $success['token'] = $user->createToken('@123*EMOOO*457##')->accessToken;
            $success['name'] = $user->name;
            return $this->sendResponse($success, 'User Login Successfully!' );
        }

       else{
            return $this->sendError('Unauthorised',['error','Unauthorised'] );
        }

    }
}
