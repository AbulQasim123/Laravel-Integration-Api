<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\personal_access_token;
use App\Models\PasswordReset;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;

class UserController extends Controller
{
        // Register
    public function Register(Request $request){
        $validator = Validator::make($request->all(),
        [
            'name' => 'required|string|min:2|max:10',
            'email' => 'required|email|min:10|unique:users',
            'password' => 'required|min:3|max:8|confirmed',
        ],
        [
            'name.required' => 'Name is required?',
            'name.string' => 'Name only should be letter. not allowed number or special char',
            'name.min' => 'Name should be Minimum 2 character long',
            'name.max' => 'Name should be Maximum 10 character long',
            'email.required' => 'Email is required?',
            'email.email' => 'Enter a valid email address',
            'email.min' => 'Email should be Maximum 10 character long',
            'email.unique' => 'Email has been already taken!',
            'password.required' => 'Password is required?',
            'password.min' => 'Password should be Minimum 2 character long',
            'password.max' => 'Password should be Maximum 8 character long',
            'password.confirmed' => 'Confirm Password does not match with password!',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'msg' => 'User Inserted Successfully.',
            'users' =>$user
        ]);
    }

        // Login
    public function Login(Request $request){
        $validator = Validator::make($request->all(),
        [
            'email' => 'required|email|min:10',
            'password' => 'required|min:3|max:8',
        ],
        [
            'email.required' => 'Email is required?',
            'email.email' => 'Enter a valid email address',
            'email.min' => 'Email should be Maximum 10 character long',
            'password.required' => 'Password is required?',
            'password.min' => 'Password should be Minimum 2 character long',
            'password.max' => 'Password should be Maximum 8 character long',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors());
        }
        if (!$token = auth()->attempt($validator->validated())) {
            return response()->json(['status' => false, 'message' => 'Username or Password Incorrect']);
        }
        return $this->RespondwithToken($token);
        // $data = DB::table('personal_access_tokens')->create([
        //     'tokenable_type' => $this->RespondwithToken('token_type'),
        //     'tokenable_id' => auth()->user()->id,
        //     'name' => auth()->user()->name,
        //     'token' => $token,
        //     'abilities' => 'Master',
        //     'expires_at' => $this->RespondwithToken('expires_in'),
        //     'created_at' => time(),
        //     'updated_at' => time(),
        // ]);
    }

    public function RespondwithToken($token){
        return response()->json([
            'status' => true,
            'access_toke' => $token,
            'token_type' => "Bearer",
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user(),
        ]);
    }
        // Logout
    public function Logout(){
        try {
            auth()->logout();
            return response()->json(['status' => true,'msg' => 'User logged out']);
        } catch (\Exception $th) {
            return response()->json(['status' => false,'msg' => $th->getMessage()]);
        }
    }
        // Profile
    public function Profile(){
        try {
            return response()->json(['status' => true,'data' => auth()->user()]);
        } catch (\Exception $th) {
            return response()->json(['status' => false,'msg' => $th->getMessage()]);
        }
    }

        // Update Profile
    public function UpdateProfile(Request $request){
        if (auth()->user()) {
            $validator = Validator::make($request->all(),
            [
                'name' => 'required|string',
                'email' => 'required|email|string',

            ],
            [
                'name.required' => 'Name is required?',
                'name.string' => 'Please enter proper name!',
                'email.required' => 'Email is required?',
                'email.email' => 'Enter a valid email address',
                'email.string' => 'Please enter proper email!',
            ]);
            if($validator->fails()){
                return response()->json($validator->errors());
            };

            $user = User::find($request->user_id);
            $user->name = $request->name;
            if ($user->email != $request->email) {
                $user->is_verified = 0;
            }
            $user->email = $request->email;
            $user->save();
            return response()->json(['status'=>true,'msg' => 'User Updated successfully', 'data' =>$user]);
        }else{
            return response()->json(['status'=>false,'msg' => 'User is not Authenticated']);
        }
    }

    // SendVerifyMail
    public function SendVerifyMail($email){
        if (auth()->user()) {
            $user = User::where('email',$email)->get();
            if (count($user) > 0) {
                $random = Str::random(40);
                $domain = URL::to('/');
                $url = $domain.'/verify-email/'.$random;

                $data['url'] = $url;
                $data['email'] = $email;
                $data['title'] = 'Email verification';
                $data['body'] = 'Please click here to verify your email';

                Mail::send('apiintegration.verifyMail',['data' => $data], function($message) use ($data){
                    $message->to($data['email'])->subject($data['title']);
                });

                $user = User::find($user[0]['id']);
                $user->remember_token = $random;
                $user->save();
                return response()->json(['status' => true,'msg' => 'Mail sent successfully.']);
            }else{
                return response()->json(['status' => false,'msg' => 'User not found']);
            }
        }else{
            return response()->json(['status' => false,'msg' => 'User is not Authenticated']);
        }
    }
        // Email Verification
    public function VerifyMail($token){
        $user = User::where('remember_token',$token)->get();
        if (count($user) > 0) {
            $datetime = Carbon::now()->format('Y-m-d H:i:s');
            $user = User::find($user[0]['id']);
            $user->remember_token = '';
            $user->is_verified = 1;
            $user->email_verified_at = $datetime;
            $user->save();
            return "<h1>Email verified successfully.</h1>";
        }else{
            return view('404');
        }
    }

    // Refreash token
    public function RefreshToken(){
        if (auth()->user()) {
            return $this->RespondwithToken(auth()->refresh());
        }else{
            return response()->json(['status' => false,'msg' => 'User is not Authenticated']);
        }
    }

    // Password Reset
    public function ForgetPassword(Request $request){
        try {
            $user = User::where('email',$request->email)->get();
            if (count($user) > 0) {
                $token = Str::random(60);
                $domain = URL::to('/');
                $url = $domain.'/password-reset?token='.$token;

                $data['url'] = $url;
                $data['email'] = $request->email;
                $data['title'] = 'Password Reset';
                $data['body'] = 'Please click on below link to reset your password!';

                Mail::send('apiintegration.forgetpasswordmail',['data' => $data], function($message) use ($data){
                    $message->to($data['email'])->subject($data['title']);
                });

                $datetime = Carbon::now()->format('Y-m-d H:i:s');
                PasswordReset::updateOrCreate(
                    ['email' => $request->email],
                    [
                        'email' => $request->email,
                        'token' => $token,
                        'created_at' => $datetime
                    ]
                );
                return response()->json(['status'=> true,'msg'=> 'Please check your mail to reset your password']);
            }else{
                return response()->json(['status'=> false,'msg'=> 'User is not Found']);
            }
        } catch (\Throwable $th) {
            return response()->json(['status'=> false,'msg'=> $th->getMessage()]);
        }
    }
        // Load form for password resetting
    public function PasswordResetload(Request $request){
        $userdata = PasswordReset::where('token',$request->token)->get();
        // echo $userdata;
        if (isset($request->token) && count($userdata) > 0) {
            $user = User::where('email',$userdata[0]['email'])->get();
            return view('apiintegration.resetpassword',compact('user'));
        }else{
            return view('404');
        }
    }

    // Submit form for password resetting
    public function PasswordResetSubmit(Request $request){
        $validator = Validator::make($request->all(),
        [
            'password' => 'required|min:3|max:8|confirmed',
        ],
        [
            'password.required' => 'Password is required?',
            'password.min' => 'Password should be Minimum 2 character long',
            'password.max' => 'Password should be Maximum 8 character long',
            'password.confirmed' => 'Confirm Password does not match with password!',
        ])->validate();

        $user = User::find($request->user_id);
        $user->password = Hash::make($request->password);
        $user->save();
        PasswordReset::where('email',$user->email)->delete();
        return "<h3>Your password has been reset successfully.</h3>";
    }
}
