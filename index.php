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

            $explorerPath = json_decode(file_get_contents($this->path.'/explorer.json'));
            
            $view->data('$analytics', $this->get('config'));
            $view->data('$explorer' , $explorerPath);
        },

        'view.scripts' => function ($event, $scripts) {
            $scripts->register('analytics-widgets', 'analytics:app/bundle/analytics-widgets.js', ['~dashboard']);
        }
    ],
];
