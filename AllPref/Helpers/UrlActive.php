<?php

namespace AllPref\Helpers;

class UrlActive
{
    public function urlActive($match)
    {
        if (preg_match("/$match/", $_SERVER['REQUEST_URI'])) {
            echo 'class="active"';
        }
    }
}
