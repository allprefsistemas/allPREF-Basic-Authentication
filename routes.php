<?php

use AllPref\Auth\BaseAuth;

$auth = $app['controllers_factory'];

require __DIR__ . '/routes/login.php';
require __DIR__ . '/routes/config.php';

$auth->get('/logout', function() use ($app) {
    BaseAuth::logout();
    return $app->redirect(URL_BASE);
});

$auth->before(function () use ($app) {
    if (!BaseAuth::validate()) {
        return $app->redirect(URL_BASE);
    }
    return null;
});

$app->mount('auth', $auth);
