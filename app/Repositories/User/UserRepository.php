<?php
namespace App\Repositories\User;

use App\Models\User;
use App\Models\UserProfile;
use App\Repositories\User\UserInterface;
use App\Http\Traits\Response;
use Validator;
use Hash;

use App\Helper;

/**
 * @author Daniel Ozeh hello@danielozeh.com.ng
 */

class UserRepository implements UserInterface {

    use Response;

    public function profile() {
        $user = User::with('user_profile')->with('user_role')->find(auth()->user()->id);

        if($user) {
            $message = [
                "user" => $user,
                "image_path" => Helper::imagePath() . '/users'
            ];

            return $this->response(
                "success",
                $message,
                200
            );
        }
    }

    public function updateProfilePicture($request) {
        $validator = Validator::make($request->all(),[
            'avatar' => 'required|mimes:png,jpg|max:2048'
        ]);

        if($validator->fails()) {
            return $this->response(
                'failed', 
                $validator->errors(),
                400
            );
        }

        if($avatar = $request->file('avatar')) {
            $size = $request->file('avatar')->getSize();
            $avatar = Helper::generateCode(12);

            $save_image = $request->avatar->move(public_path('/users'), $avatar);
        }
        $user_profile = UserProfile::where('user_id', auth()->user()->id)->first();
        $user_profile->avatar = $avatar;

        $user_profile->save();

        return $this->response(
            'success', 
            'Profile Picture Updated',
            200
        );        
    }

    public function updateProfileCoverPhoto($request) {
        $validator = Validator::make($request->all(),[
            'cover_photo' => 'required|mimes:png,jpg|max:2048'
        ]);

        if($validator->fails()) {
            return $this->response(
                'failed', 
                $validator->errors(),
                400
            );
        }

        if($cover_photo = $request->file('cover_photo')) {
            $size = $request->file('cover_photo')->getSize();
            $cover_photo = Helper::generateCode(15);

            $save_image = $request->cover_photo->move(public_path('/users'));
        }
        $user_profile = UserProfile::where('user_id', auth()->user()->id)->first();
        $user_profile->cover_photo = $cover_photo;

        $user_profile->save();

        return $this->response(
            'success',
            'User Profile Cover Photo Updated',
            200
        );
        
    }

    public function updateProfile($request) {
        $validator = Validator::make($request->all(),[
            'first_name' => 'required|string',
            "last_name" => "required|string",
            "username" => "string",
            "phone_number" => "string",
            "occupation" => "string",
            "gender" => "",
            "date_of_birth" => "",
            "about" => "",
            "address" => "",
            "state" => "",
            "country" => ""
        ]);

        if($validator->fails()) {
            return $this->response(
                'failed', 
                $validator->errors(),
                400
            );
        }

        $user_profile = UserProfile::where('user_id', auth()->user()->id)->first();
        $user_profile->phone_number = $request->phone_number;
        $user_profile->gender = $request->gender;
        $user_profile->date_of_birth = $request->date_of_birth;
        $user_profile->address = $request->address;
        $user_profile->state = $request->state;
        $user_profile->country = $request->country;
        $user_profile->about = $request->about;
        $user_profile->occupation = $request->occupation;

        $user_profile->save();

        $user = User::find(auth()->user()->id);
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->username = $request->username;

        $user->save();

        return $this->response(
            'success',
            'Profile Updated',
            200
        );
        
    }

    public function changePassword($request) {
        $user_id = auth()->user()->id;

        $validator = Validator::make($request->all(), [
            'old_password' => 'required|string',
            'new_password' => 'required|string',
            'confirm_password' => 'required|string',
        ]);

        if($validator->fails()) {
            return $this->response(
                'failed', 
                $validator->errors(),
                400
            );
        }

        if($request->new_password != $request->confirm_password) {
            return $this->response(
                'failed',
                'Passwords do not match',
                401
            );
        }

        $user = User::find($user_id);

        if (Hash::check($request->old_password, $user->password)) {
            $user->password = Hash::make($request->new_password);
            $user->save();

            return $this->response(
                'success', 
                'Password Updated Successfully!',
                200
            );

        } else {
            return $this->response(
                'failed', 
                'Your Old Password Credential is Invalid!',
                401
            );
        }
    }

}