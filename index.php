<?php
return [
    'name' => 'analytics',

    'autoload' => [
        'GreenCheap\\Analytics\\' => 'src'
    ],

    'routes' => [
        'api/analytics' => [
            'name' => '@api/analytics',
            'controller' => 'GreenCheap\\Analytics\\Controller\\ApiReportingController'
        ]
    ],

    'config' => [
        'view_id' => ''
    ],

    'events' => [

        'view.layout' => function ($event, $view) use ($app) {
            if (!$app->isAdmin()) {
                return;
            }
            $view->data('$analytics', $this->get('config'));
        },

        'view.scripts' => function ($event, $scripts) {
            $scripts->register('analytics-widgets', 'analytics:app/bundle/analytics-widgets.js', ['~dashboard']);
        }
    ],
];
