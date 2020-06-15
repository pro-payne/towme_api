<?php

use App\Exceptions\PermissionDeniedException;

if (!file_exists('checkUser')) {
    /**
     * Validates user making the request
     *
     * @param user data
     * User data
     * @param account data
     * Account data
     *
     * @return throw error if not validated true
     */
    function checkUser($user, $account)
    {
        if ($user->id !== $account->id) {
            throw new PermissionDeniedException;
        }
    }
}
