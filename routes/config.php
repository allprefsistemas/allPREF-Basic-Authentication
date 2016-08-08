<?php

$ctrl = AllPref\Controllers\ConfigController::class;

$auth->get('/config/profile/', "$ctrl::profile");
$auth->post('/config/update/', "$ctrl::updateDetails");
$auth->post('/config/profile/avatar/', "$ctrl::avatar");
$auth->post('/config/newPassword/', "$ctrl::newPassword");

unset($ctrl);
