<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Traits\Response;

use App\Repositories\Auth\AuthInterface;


/**
 * @author Daniel Ozeh hello@danielozeh.com.ng
 */
class AuthController extends Controller
{
    use Response;
    protected $auth;

    public function __construct(AuthInterface $auth) {
        $this->auth = $auth;
    }

    public function register(Request $request) {
        try {
            return $this->auth->register($request);

        } catch (\Exception $e) {
            return $this->response(
                "failed",
                $e->getMessage(),
                500
            );
        }
    }

    public function login(Request $request) {

        try {
            return $this->auth->login($request);

        } catch (\Exception $e) {
            return $this->response(
                "failed",
                $e->getMessage(),
                500
            );
            
        }
    }

    public function resendVerificationCode(Request $request) {
        try {
            return $this->auth->resendVerificationCode($request);

        } catch (\Exception $e) {
            return $this->response(
                "failed",
                $e->getMessage(),
                500
            );
        }
    }

    public function verify(Request $request) {
        try {
            return $this->auth->verify($request);

        } catch (\Throwable $th) {
            return $this->response(
                "failed",
                $e->getMessage(),
                500
            );
        }
    }

    public function forgotPassword(Request $request) {
        try {
            return $this->auth->forgotPassword($request);

        } catch (\Exception $e) {
            return $this->response(
                "failed",
                $e->getMessage(),
                500
            );
        }
    }

    public function resetPassword(Request $request, $token) {
        try {
            return $this->auth->resetPassword($request, $token);

        } catch (\Exception $e) {
            return $this->response(
                "failed",
                $e->getMessage(),
                500
            );
        }
    }

    public function logout() {
        try {
            return $this->auth->logout();

        } catch (\Exception $e) {
            return $this->response(
                "failed",
                $e->getMessage(),
                500
            );
        }
    }

    public function refresh() {
        try {
            return $this->auth->refresh();

        } catch (\Exception $e) {
            return $this->response(
                "failed",
                $e->getMessage(),
                500
            );
        }
    }
}
