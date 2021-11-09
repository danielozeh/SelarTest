<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Traits\Response;

use App\Repositories\User\UserInterface;


/**
 * @author Daniel Ozeh hello@danielozeh.com.ng
 */
class UserController extends Controller
{
    use Response;
    protected $user;

    public function __construct(UserInterface $user) {
        $this->user = $user;
    }

    public function profile() {
        try {
            return $this->user->profile();

        } catch (\Exception $e) {
            return $this->response(
                "failed",
                $e->getMessage(),
                500
            );
        }
    }

    public function updateProfilePicture(Request $request) {
        try {
            return $this->user->updateProfilePicture($request);

        } catch (\Exception $e) {
            return $this->response(
                "failed",
                $e->getMessage(),
                500
            );
        }
    }

    public function updateProfileCoverPhoto(Request $request) {
        try {
            return $this->user->updateProfileCoverPhoto($request);

        } catch (\Exception $e) {
            return $this->response(
                "failed",
                $e->getMessage(),
                500
            );
        }
    }

    public function updateProfile(Request $request) {
        try {
            return $this->user->updateProfile($request);

        } catch (\Exception $e) {
            return $this->response(
                "failed",
                $e->getMessage(),
                500
            );
        }   
    }

    public function changePassword(Request $request) {
        try {
            return $this->user->changePassword($request);

        } catch (\Exception $e) {
            return $this->response(
                "failed",
                $e->getMessage(),
                500
            );
        }
    }
}
