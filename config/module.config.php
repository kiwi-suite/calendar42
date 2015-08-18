<?php
namespace Calendar42;

return [
    'view_manager'  => [
        'template_path_stack' => [
            __NAMESPACE__ => __DIR__ . '/../view',
        ],
    ],

    'migration'     => [
        'directory' => [
            __NAMESPACE__ => __DIR__ . '/../data/migrations'
        ],
    ],

    'form_elements' => [
        'factories' => [
            'calendar' => 'Calendar42\FormElements\Service\CalendarFactory',
        ],
    ],
];
