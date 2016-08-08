<?php

namespace AllPref\Helpers;

require_once __DIR__.'/../../bootstrap.php';

class Doctrine
{
    private $doctrine;

    public function __construct()
    {
        $this->doctrine = $GLOBALS['entityManager'];
    }

    public function getDoctrine()
    {
        return $this->doctrine;
    }
}