<?php

namespace App\Repositories\Auth;

/**
 * @author Daniel Ozeh hello@danielozeh.com.ng
 */

interface AuthInterface {

    public function register($request);

    public function login($request);

    public function logout();

    public function refresh();

    public function resendVerificationCode($request);

    public function verify($request);

    public function forgotPassword($request);

    public function resetPassword($request, $token);
}