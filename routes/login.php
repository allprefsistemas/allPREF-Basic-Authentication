<?php

$ctrl = AllPref\Controllers\LoginController::class;

$app->get('/', "$ctrl::index");
$app->post('/login', "$ctrl::login");
$app->get('/recover', "$ctrl::recover");
$app->post('/newpassword', "$ctrl::newpassword");
$app->get('/signup', "$ctrl::signup");
$app->post('/newuser', "$ctrl::newuser");

$auth->get('/dashboard', "$ctrl::dashboard");

unset($ctrl);
