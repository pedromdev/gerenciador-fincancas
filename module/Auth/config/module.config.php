<?php

namespace Auth;

return array(
    'service_manager' => array(
        'aliases' => array(
            'AuthManager' => 'Auth\Manager\AuthManager',
            'AuthManagerConfig' => 'Auth\Manager\AuthManagerConfig',
        ),
        'factories' => array(
            'Auth\Manager\AuthManager' => 'Auth\Manager\AuthManagerFactory',
            'Auth\Manager\AuthManagerConfig' => 'Auth\Manager\AuthManagerConfigFactory',
        ),
    ),
    'auth_manager' => array(
        'aliases' => array(),
        'factories' => array(),
        'invokables' => array(),
    ),
);