<?php

namespace App\Repositories\Auth;

use App\Models\User;
use App\Models\UserProfile;
use App\Models\PasswordReset;
use App\Repositories\Auth\AuthInterface;
use App\Http\Traits\Response;
use Validator;

use App\Helper;
use Hash;
use Mail;
use App\Mail\WelcomeMail;
use App\Mail\ForgotPasswordMail;


/**
 * @author Daniel Ozeh hello@danielozeh.com.ng
 */

class AuthRepository implements AuthInterface {

    use Response;

    public function register($request) {
        
        $validator = Validator::make($request->all(),[
            'first_name' => 'required|string|between:2,100',
            'last_name' => 'required|string|between:2,100',
            'email' => 'required|string|max:100|unique:users',
            'password' => 'required|string|confirmed|min:6'
        ]);

        if($validator->fails()) {
            return $this->response(
                "failed",
                $validator->errors(),
                400
            );
        }

        $ip_address = Helper::getRealIpAddr();
        $user_location = Helper::getRealUserLocation();
        $verification_code = Helper::generateCode(10);

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role_id' => 2,
            'verification_code' => $verification_code
        ]);

        $user_id = $user->id;

        $user_profile = UserProfile::create([
            'user_id' => $user_id,
            'registered_ip' => $ip_address,
            "state" => $user_location['state'],
            "country" => $user_location['country']
        ]);

        //$myEmail = 'hello@danielozeh.com.ng';

        $details = [
            'subject' => 'Welcome Mail Daniel Ozeh',
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'verification_code' => $verification_code
        ];

        Mail::to($request->email)->send(new WelcomeMail($details));

        return $this->response(
            "success",
            "Account Created Successfully.. Proceed to Login",
            201
        );
    }

    public function login($request) {

        $validator = Validator::make($request->only('email', 'password'), [
            'email' => 'required|email',
            'password' => 'required|string|min:6'
        ]);

        if($validator->fails()) {
            return $this->response(
                "failed",
                $validator->errors(),
                400
            );
        }

        if(!$token = auth()->attempt($validator->validated())) {
            return $this->response(
                "failed",
                "Invalid Credentials",
                401
            );
        }

        if(auth()->user()->is_verified == 0) {
            return $this->response(
                "failed",
                "Account is not verified!",
                401
            );
        }

        if(auth()->user()->is_active == 0) {
            return $this->response(
                "failed",
                "Account is Blocked!",
                401
            );
        }

        return $this->createNewToken($token);
    }

    protected function createNewToken($token){
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 86400,
            'user' => auth()->user()
        ]);
    }

    public function logout() {
        auth()->logout();
        return $this->response(
            "success",
            "Account signed out successfully",
            200
        );
    }

    public function refresh() {
        return $this->createNewToken(auth()->refresh());
    }

    public function resendVerificationCode($request) {

        $validator = Validator::make($request->only('email'), [
            'email' => 'required|email',
        ]);

        if($validator->fails()) {
            return $this->response(
                "failed",
                $validator->errors(),
                400
            );
        }

        $verification_code = Helper::generateCode(10);

        $user = User::where('email', $request->email)->first();
        if($user) {
        	if($user->is_verified == 1) {
                return $this->response(
                    "failed",
                    "You have already verified your account. Proceed to Login!",
                    401
                );
        	}

        	if($user->is_active == 0) {
                return $this->response(
                    "failed",
                    "You account has been blocked so you cannot verify your account",
                    401
                );
        	}
            $user->verification_code = $verification_code;
            $user->save();

            $details = [
                'subject' => 'Verification Mail from Guinea Insurance Plc',
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'verification_code' => $verification_code
            ];

            Mail::to($request->email)->send(new WelcomeMail($details));

            return $this->response(
                "success",
                "Verification Code Resent",
                201
            );
        }

        return $this->response(
            "failed",
            "Invalid Email Address",
            401
        );
        
    }

    public function verify($request) {

        $validator = Validator::make($request->only('verification_code'), [
            'verification_code' => 'required',
        ]);

        if($validator->fails()) {
            return $this->response(
                "failed",
                $validator->errors(),
                400
            );
        }

        $verify = User::where('verification_code', $request->verification_code)->get();

        //return response()->json(['status' => 'failed', 'message' => $verify], 404);
        if(count($verify) > 0) {
            $id = $verify[0]->id;
            $user = User::find($id);

            if($user->is_verified == 1) {
                return $this->response(
                    "failed",
                    "Account Already Verified",
                    401
                );
            }
            $user->verification_code = $request->verification_code;
            $user->is_verified = 1;

            $user->save();

            return $this->response(
                "success",
                "Account Verified Successfully!",
                200
            );
        }
        else {
            return $this->response(
                "failed",
                "Invalid Verification Code",
                401
            );
        }
    }

    public function forgotPassword($request) {

        $validator = Validator::make($request->only('email'), [
            'email' => 'required|email',
        ]);

        if($validator->fails()) {
            return $this->response(
                "failed",
                $validator->errors(),
                400
            );
        }
        //get user_id belonging to email
        $user_detail = User::where('email', $request->email)->first();

        if(!$user_detail) {
            return $this->response(
                "failed",
                "Email Address Does not exist!",
                401
            );
        }

        //generate a new password and send to the user
        $token = Helper::generateCode(12);

        //delete all previous token
        $password_reset = PasswordReset::where('email', $request->email)->delete();

        PasswordReset::create([
            'email' => $request->email,
            'token' => $token,
            'created_at' => date('Y-m-d H:i:s')
        ]);

        $details = [
            'subject' => 'Reset Password',
            'first_name' => $user_detail->first_name,
            'last_name' => $user_detail->last_name,
            'token' => $token
        ];

        Mail::to($request->email)->send(new ForgotPasswordMail($details));

        return $this->response(
            "success",
            "An email containing your reset password instructions has been sent",
            200
        );
    }


    public function resetPassword($request, $token) {
        
        $validator = Validator::make($request->all(),[
            'password' => 'required|string|confirmed|min:6'
        ]);

        if($validator->fails()) {
            return $this->response(
                "failed",
                $validator->errors(),
                400
            );
        }

        //verify if token is correct
        $token_exist = PasswordReset::where('token', $token)->first();
        if($token_exist) {
            //get user email
            $email = $token_exist->email;
            
            $user = User::where('email', $email)->update([
                'password' => bcrypt($request->password)
            ]);

            $password_reset = PasswordReset::where('email', $email)->delete();

            return $this->response(
                "success",
                "Password Reset is successful",
                200
            );
        }

        return $this->response(
            "failed",
            "Invalid Token",
            401
        );
    }
}