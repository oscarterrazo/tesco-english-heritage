<?php

return array(
    'routes' => array(
        'home' => [
            'type' => 'Zend\Mvc\Router\Http\Literal',
            'options' => [
                'route' => '/',
                'defaults' => [
                    'controller' => 'TescoRedemptionPortal\Controller\Token',
                    'action' => 'verify'
                ]
            ]
        ],
        'token' => [
            'type' => 'segment',
            'options' => [
                'route' => '/token[/:action][/:tokenId][/:value]',
                'constraints' => [
                    'action' => '[a-zA-Z][a-zA-Z0-9_-]*'
                ],
                'defaults' => [
                    'controller' => 'TescoRedemptionPortal\Controller\Token',
                    'action' => 'verify'
                ]
            ]
        ]
    )
);