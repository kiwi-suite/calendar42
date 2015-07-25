<?php
namespace Calendar42;

return [
    'router' => [
        'routes' => [
            'admin' => [
                'child_routes' => [

                    'calendar' => [
                        'type'          => 'Zend\Mvc\Router\Http\Literal',
                        'options'       => [
                            'route'    => 'calendar/',
                            'defaults' => [
                                'action'     => 'calendar',
                                'controller' => __NAMESPACE__ . '\Calendar',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes'  => [
                            'calendar' => [
                                'type'    => 'Zend\Mvc\Router\Http\Segment',
                                'options' => [
                                    'route'    => ':id/',
                                    'defaults' => [
                                        'action' => 'calendar'
                                    ],
                                ],
                            ],
                            'list' => [
                                'type'    => 'Zend\Mvc\Router\Http\Segment',
                                'options' => [
                                    'route'    => 'list/',
                                    'defaults' => [
                                        'action' => 'list'
                                    ],
                                ],
                            ],
                            'edit'     => [
                                'type'    => 'Zend\Mvc\Router\Http\Segment',
                                'options' => [
                                    'route'    => 'edit/:id/',
                                    'defaults' => [
                                        'action'     => 'detail',
                                        'isEditMode' => true,
                                    ],
                                ],
                            ],
                            'add'      => [
                                'type'    => 'Zend\Mvc\Router\Http\Literal',
                                'options' => [
                                    'route'    => 'add/',
                                    'defaults' => [
                                        'action'     => 'detail',
                                        'isEditMode' => false,
                                    ],
                                ],
                            ],
                            'delete'   => [
                                'type'    => 'Zend\Mvc\Router\Http\Literal',
                                'options' => [
                                    'route'    => 'delete/',
                                    'defaults' => [
                                        'action' => 'delete',
                                    ],
                                ],
                            ],
                        ],
                    ],

                    'event'     => [
                        'type'    => 'Zend\Mvc\Router\Http\Literal',
                        'options' => [
                            'route'    => 'event/',
                            'defaults' => [
                                'action'     => 'index',
                                'controller' => __NAMESPACE__ . '\Event',
                                'isEditMode' => true,
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes'  => [
                            'edit'     => [
                                'type'    => 'Zend\Mvc\Router\Http\Segment',
                                'options' => [
                                    'route'    => 'edit/:id/',
                                    'defaults' => [
                                        'action'     => 'detail',
                                        'isEditMode' => true,
                                    ],
                                ],
                            ],
                            'add'      => [
                                'type'    => 'Zend\Mvc\Router\Http\Literal',
                                'options' => [
                                    'route'    => 'add/',
                                    'defaults' => [
                                        'action'     => 'detail',
                                        'isEditMode' => false,
                                    ],
                                ],
                            ],
                            'delete'   => [
                                'type'    => 'Zend\Mvc\Router\Http\Literal',
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
