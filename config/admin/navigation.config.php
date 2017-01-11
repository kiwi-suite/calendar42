<?php
namespace Calendar42;

return [
    'navigation' => [
        'containers' => [
            'admin42' => [
                'content' => [
                    'pages' => [
                        'calendar' => [
                            'label' => 'label.calendar',
                            'route' => 'admin/calendar',
                            'icon'  => 'fa fa-calendar fa-fw',
                            'order' => 8000,
                            'permission' => 'route/admin/calendar'
                        ],
                    ],
                ]
            ],
        ],
    ],
];
