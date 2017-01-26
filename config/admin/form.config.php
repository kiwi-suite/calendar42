<?php
namespace Calendar42;


use Calendar42\FormElements\Service\CalendarFactory;
use Calendar42\FormElements\Service\MultiCalendarFactory;

return [
    'form_elements' => [
        'factories' => [
            'calendar'      => CalendarFactory::class,
            'multiCalendar' => MultiCalendarFactory::class,
        ],
    ],
];
