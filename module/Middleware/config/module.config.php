<?php

namespace Middleware;

return array(
    'service_manager' => array(
        'invokables' => array(
            __NAMESPACE__ . '\Listener\RouteOptions' => Listener\RouteOptionsListener::class,
            __NAMESPACE__ . '\Listener\HttpFilter' => Listener\HttpFilterListener::class,
        ),
    ),
    
    'listeners' => array(
        __NAMESPACE__ . '\Listener\RouteOptions',
        __NAMESPACE__ . '\Listener\HttpFilter',
    ),
);