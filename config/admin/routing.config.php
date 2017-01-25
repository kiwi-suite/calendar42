<?php
namespace Calendar42;

use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

return [
    'router' => [
        'routes' => [
            'admin' => [
                'child_routes' => [

                    'calendar' => [
                        'type'          => Literal::class,
                        'options'       => [
                            'route'    => 'calendar/',
                            'defaults' => [
                                'action'     => 'calendar',
                                'controller' => __NAMESPACE__.'\Calendar',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes'  => [
                            'calendar' => [
                                'type'          => Segment::class,
                                'options'       => [
                                    'route'    => ':id/',
                                    'defaults' => [
                                        'action' => 'calendar'
                                    ],
                                ],
                                'may_terminate' => true,
                                'child_routes'  => [
                                    'ical' => [
                                        'type'    => Segment::class,
                                        'options' => [
                                            'route'    => 'ical/',
                                            'defaults' => [
                                                'action' => 'ical'
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                            'ical'     => [
                                'type'          => Literal::class,
                                'options'       => [
                                    'route'    => 'ical/',
                                    'defaults' => [
                                        'action' => 'ical'
                                    ],
                                ],
                                'may_terminate' => true,
                            ],
                            'events'   => [
                                'type'    => Segment::class,
                                'options' => [
                                    'route'    => ':id/events/',
                                    'defaults' => [
                                        'action' => 'events'
                                    ],
                                ],
                            ],
                            'list'     => [
                                'type'    => Segment::class,
                                'options' => [
                                    'route'    => 'list/',
                                    'defaults' => [
                                        'action' => 'list'
                                    ],
                                ],
                            ],
                            'edit'     => [
                                'type'    => Segment::class,
                                'options' => [
                                    'route'    => 'edit/:id/',
                                    'defaults' => [
                                        'action'     => 'detail',
                                        'isEditMode' => true,
                                    ],
                                ],
                            ],
                            'add'      => [
                                'type'    => Literal::class,
                                'options' => [
                                    'route'    => 'add/',
                                    'defaults' => [
                                        'action'     => 'detail',
                                        'isEditMode' => false,
                                    ],
                                ],
                            ],
                            'delete'   => [
                                'type'    => Literal::class,
                                'options' => [
                                    'route'    => 'delete/',
                                    'defaults' => [
                                        'action' => 'delete',
                                    ],
                                ],
                            ],
                        ],
                    ],

                    'event' => [
                        'type'          => Literal::class,
                        'options'       => [
                            'route'    => 'event/',
                            'defaults' => [
                                'action'     => 'index',
                                'controller' => __NAMESPACE__.'\Event',
                                'isEditMode' => true,
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes'  => [
                            'edit'   => [
                                'type'    => Segment::class,
                                'options' => [
                                    'route'    => 'edit/:id/',
                                    'defaults' => [
                                        'action'     => 'detail',
                                        'isEditMode' => true,
                                    ],
                                ],
                            ],
                            'add'    => [
                                'type'    => Literal::class,
                                'options' => [
                                    'route'    => 'add/',
                                    'defaults' => [
                                        'action'     => 'detail',
                                        'isEditMode' => false,
                                    ],
                                ],
                            ],
                            'delete' => [
                                'type'    => Literal::class,
                                'options' => [
                                    'route'    => 'delete/',
                                    'defaults' => [
                                        'action' => 'delete',
                                    ],
                                ],
                            ],
                        ],
                    ],

                ],
            ],
        ],
    ],
];
