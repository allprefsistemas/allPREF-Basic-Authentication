<?php

namespace AllPref\Controllers;

use AllPref\Models\User\UserModel;
use Silex\Application;
use AllPref\Auth\BaseAuth;

class LoginController
{

    public function index()
    {
        return view()->render('login/login.html.twig', ['token' => BaseAuth::tokenGen()]);
    }

    public function login(Application $application)
    {
        $request = $application['request_stack']->getCurrentRequest()->request;
        $auth = new BaseAuth;
        if (
            (true === $auth->tokenVerify($request->get('_token'))) &&
            ($auth->login($request->get('email'), $request->get('senha')))
        ) {
            $auth->grant($request->get('email'));
            return $application->redirect(URL_AUTH . '/dashboard');
        }
        session()->set('error', 'Email or Password invalid');
        return $application->redirect(URL_BASE);
    }

    public function recover()
    {
        return view()->render('login/recover.html.twig', ['token' => BaseAuth::tokenGen()]);
    }

    public function newpassword(Application $application)
    {
        $request = $application['request_stack']->getCurrentRequest()->request;
        $auth = new BaseAuth;
        if (false === $auth->tokenVerify($request->get('_token'))) {
            return $application->redirect(URL_BASE);
        }
        $recoverPassword = $auth->recoverPassword($request->get('email'));
        if ($recoverPassword) {
            session()->set('success', 'See your new password in your email');
            return $application->redirect(URL_BASE);
        }
        session()->set('error', 'Email not found');
        return $application->redirect(URL_BASE . '/recover');
    }

    public function signup()
    {
        return view()->render('login/signup.html.twig', ['token' => BaseAuth::tokenGen()]);
    }

    public function newuser(Application $application)
    {
        $request = $application['request_stack']->getCurrentRequest()->request;
        $auth = new BaseAuth;
        if (false === $auth->tokenVerify($request->get('_token'))) {
            return $application->redirect(URL_BASE);
        }

        if (!$request->get('password') || !$request->get('repeatPassword') || !$request->get('password') || !$request->get('repeatPassword')) {
            session()->set('error', 'All fields are required');
            return $application->redirect(URL_BASE . '/signup');
        }

        if ($request->get('password') !== $request->get('repeatPassword')) {
            session()->set('error', 'You should repeat the password correctly');
            return $application->redirect(URL_BASE . '/signup');
        }

        $emailExists = $auth->seeEmailExists($request->get('email'));
        if ($emailExists) {
            session()->set('error', 'Email already registered');
            return $application->redirect(URL_BASE . '/signup');
        }

        $newUser = $auth->newuser($request->all());

        if ($newUser) {
            session()->set('success', 'Your registration was successful');
            return $application->redirect(URL_BASE);
        }
        session()->set('error', 'Has occurred a error while making your registration.');
        return $application->redirect(URL_BASE . '/signup');
    }

    public function dashboard()
    {
        $userModel = new UserModel;
        $currentUser = $userModel->getProfile();
        $users = $userModel->getAll();
        return view()->render('dashboard/dashboard.html.twig', ['currentUser' => $currentUser, 'users' => $users]);
    }
}
