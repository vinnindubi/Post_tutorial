<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
class AuthController extends Controller
{
    public function register(Request $request){
        $validator = Validator::make($request->all(), [
            "firstname"=>"required",
            "middlename"=>"required",
            "lastname"=>"required",
            "phonenumber"=>"required|numeric",
            "email"=> "required|email",
            "password"=>"required",
            "confirmpassword"=>"required|same:password"
            
        ]);
        
        if ($validator->fails()){
            return response()->json([
                "status"=>0,
                "message"=>"failed to register",
                "data"=>$validator->errors()->all()
            ]);
        }
        $user=User::create([
            "firstname"=>$request->firstname,
            "middlename"=> $request->middlename,
            "lastname"=>$request->lastname,
            "phonenumber"=> $request->phonenumber,
            "email"=> $request->email,
            "password"=> bcrypt($request->password),
        ]);
        $token = $user->createToken('MyAppToken')->accessToken;

       
        
        return response()->json([
            "status"=>1,
            "message"=> "user registered",
            "token"=> $token,
            "data"=>$user
        ]);
    }
    public function login(Request $request){
        
    // Validate input
    $request->validate([
        'email' => 'required|email',
        'password' => 'required'
    ]);

    // Find user by email
    $user = User::where('email', $request->email)->first();

    // Check if user exists and password is correct
    if ($user && Hash::check($request->password, $user->password)) {
        // Create access token
        $token = $user->createToken('MyAppToken')->accessToken;

        return response()->json([
            'status' => 1,
            'message' => 'Login successful',
            'token' => $token
        ]);
    }

    // If login fails
    return response()->json([
        'status' => 0,
        'message' => 'Email or password is incorrect'
    ], 401);
}

    
    public function logout(){
        $user=Auth::user();
        $user->token()->revoke();
        return response()->json([
            "status"=> 1,
            "message"=> "user logged out"
        ]);
    }
    public function index(Request $request)
                {
                    $data=User::paginate();
                    if(!$data){
                        return response()->json([
                            "status"=> 1,
                            "message"=>"loading users failed",
                            "data"=>$data]);
                    }
                    return response()->json([
                        "status"=> 1,
                        "data"=>$data]);
                }
    public function show($id)
                {  $data=User::find($id);
                    if(!$data){
                        return response()->json([
                            "status"=> 0,
                            "message"=>"failed to load users"]);
                    }
                    return response()->json([
                        "status"=> 1,
                        "data"=>$data]);

                }
    public function update(Request $request, $id)
                { $user=User::find($id);
                    if(!$user){
                        return response()->json([
                            "status"=> 0,
                            "message"=> "user not found",
                            "data"=>$user]);
                    }
                    $validated= $request->validate([
                        "firstname"=> "required|sometimes",
                        "middlename"=> "|sometimes",
                        "lastname"=> "required|sometimes",
                        "phonenumber"=> "required|sometimes",
                    ]);
                   $user->update($validated);
                   return response()->json([
                    "status"=> 1,
                    "data"=>$user
                   ]);
                    
                }                        
    public function destroy(Request $request,$id)
                {
                    $user=User::find( $id );
                    if(!$user){
                        return response()->json([
                            "status"=> 0,
                            "message"=> "user does not exist"
                        ]);
                    }
                    
                    $user->delete();
            
                    return response()->json([
                        "status"=> 1,
                        "message"=> "user deleted successfully",
                        "data"=>$user

                    ]);
                }

}