<?php

return [
    'debugMode' => true,
    'routes'    => [
        '^/test/get/?$'                                => [
            'method' => 'GET',
            'route'  => 'test//get'
        ],
        '^/test/post/?$'                               => [
            'method' => 'POST',
            'route'  => 'test//post'
        ],
        '^/test/?$'                                    => [
            'route' => '/test/'
        ],
        '^/news/([0-9]{2}\.[0-9]{2}\.[0-9]{4})/(\d+)$' => [
            'route' => 'news/bydate/'
        ],
        '^/error/?$'                                    => [
            'route' => '//error'
        ]
    ]
];