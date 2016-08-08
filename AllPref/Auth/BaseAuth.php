<?php

namespace AllPref\Auth;

use AllPref\Models\User\UserModel;
use Hashids\Hashids;

class BaseAuth
{
    public static function tokenGen()
    {
        $token = sha1(rand(111111, 999999));
        session()->set('_token', $token);
        return $token;
    }

    public function tokenVerify($token)
    {
        if (!session()->has('_token')) {
            return false;
        }
        if ($token !== session()->get('_token')) {
            return false;
        }
        return true;
    }

    public function login($email, $password)
    {
        $userProvider = new UserModel;
        $user = $userProvider->getUserByEmail($email);
        if ($user && $this->checkHash($password, $user->getPassword())) {
            return true;
        }
        return false;
    }

    public function checkHash($password, $hash)
    {
        if (filter_var($password, FILTER_SANITIZE_STRING)) {
            return password_verify($password, $hash);
        } else {
            return false;
        }
    }

    public function grant($email)
    {
        $userProvider = new UserModel;
        $user = $userProvider->getUserByEmail($email);
        $hashids = new Hashids(HASHID_SALT, HASHID_LEVEL);
        $hashid = $hashids->encode($user->getId());
        session()->set('loggedUser', $hashid);
        session()->set('logged', true);
        session()->register('30 min');
    }

    public function recoverPassword($email):bool
    {
        $userProvider = new UserModel;
        $newPassword = $userProvider->recoverPassword($email);
        if ($newPassword) {
            return true;
        }
        return false;
    }

    public function seeEmailExists($email):bool
    {
        $userProvider = new UserModel;
        if ($userProvider->getUserByEmail($email)) {
            return true;
        }
        return false;
    }

    public function newuser($request)
    {
        $name = filter_var($request['name'], FILTER_SANITIZE_STRING);
        $email = filter_var(filter_var($request['email'], FILTER_SANITIZE_EMAIL), FILTER_VALIDATE_EMAIL);
        $password = filter_var($request['password'], FILTER_SANITIZE_STRING);

        $user = new UserModel;

        $newUser = $user->createUser($name, $email, $password);
        if ($newUser) {
            return true;
        }
        return false;
    }

    public static function validate()
    {
        session()->valid();
        if (session()->has('logged')) {
            return true;
        }
        return false;
    }
    
    public static function logout()
    {
        session()->destroy();
    }
}
