<?php

return [
    'GET|/' => \Smart\Resale\Controller\Wip::class,

    'GET|/login' => \Smart\Resale\Controller\User\LoginFormController::class,
    'POST|/login' => \Smart\Resale\Controller\User\LoginController::class,

    'GET|/signup' => \Smart\Resale\Controller\User\SignUpFormController::class,
    'POST|/signup' => \Smart\Resale\Controller\User\SignUpController::class,

    'GET|/logout' => \Smart\Resale\Controller\User\LogoutController::class,

    'GET|/config/user' => \Smart\Resale\Controller\User\UserConfigController::class,
    'POST|/config/user-update' => \Smart\Resale\Controller\User\UpdateUserController::class,
    'POST|/config/password-update' => \Smart\Resale\Controller\User\UpdatePasswordController::class,
    'POST|/config/disable-account' => \Smart\Resale\Controller\User\DisableAccountController::class,
];
