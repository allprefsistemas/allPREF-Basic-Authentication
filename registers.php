<?php

require __DIR__ . '/vendor/autoload.php';

use Silex\Provider\TwigServiceProvider as Twig;
use AllPref\Helpers\Session;
use AllPref\Helpers\UrlActive;

$app->register(new Twig, [ 'twig.path' => __DIR__ . '/views']);

$app['url_base'] = URL_BASE;
$app['url_public'] = URL_PUBLIC;
$app['url_auth'] = URL_AUTH;

$app['my_session'] =  new Session('AllPref');

$app['html'] = function (){
    return new UrlActive;
};

function view() {
    global $app;
    return $app['twig'];
}

function session() {
    global $app;
    return $app['my_session'];
}
