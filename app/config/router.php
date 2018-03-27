<?php

$router = $di->getRouter();

// Define your routes here

$router->add(
    '/admin/:controller/:action',
    [
        'namespace'=>'admin',
        'controller'=>1,
        'action'=>2,
    ]
);
$router->add(
    '/:controller/:action',
    [
        'controller'=>1,
        'action'=>2,
    ]
);

$router->handle();
