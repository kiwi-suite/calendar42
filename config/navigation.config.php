<?php

return [
    'navigation' => [
        'containers' => [
            'admin42' => [
                'content' => [
                    'pages' => [
                        'calendar' => [
                            'options' => [
                                'label' => 'label.calendar',
                                'route' => 'admin/calendar',
                                'icon'  => 'fa fa-calendar fa-fw',
                                'order' => 8000,
                                'permission' => 'route/admin/calendar'
                            ],
                        ],
                    ],
                ]
            ],
        ],
    ],
];
