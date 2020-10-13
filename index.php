<?php
return [
    'name' => 'metrica',

    'autoload' => [
        'GreenCheap\\Metrica\\' => 'src'
    ],

    'routes' => [
        '/metrica' => [
            'name' => '@metrica',
            'controller' => 'GreenCheap\\Metrica\\Controller\\CallBackController'
        ]
    ],

    'settings' => 'metrica-settings',

    'config' => [
        'id' => '',
        'password' => '',
        'access_token' => ''
    ],

    'events' => [

        'view.layout' => function ($event, $view) use ($app) {

            if (!$app->isAdmin()) {
                return;
            }
            $view->data('$metrica', $this->get('config'));
        },

        'view.scripts' => function ($event, $scripts) {
            $scripts->register('metrica-settings', 'metrica:app/bundle/metrica-settings.js', ['~extensions', 'input-tree']);
            $scripts->register('metrica-widgets', 'metrica:app/bundle/metrica-widgets.js', ['~dashboard']);
        },

    ],
];
