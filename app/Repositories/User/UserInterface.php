<?php

namespace App\Repositories\User;

/**
 * @author Daniel Ozeh hello@danielozeh.com.ng
 */

interface UserInterface {
    
    public function profile();

    public function updateProfilePicture($request);

    public function updateProfileCoverPhoto($request);

    public function updateProfile($request);

    public function changePassword($request);
}