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
                                'action'     => 'index',
                                'controller' => __NAMESPACE__ . '\Calendar',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes'  => [
                            'edit'   => [
                                'type'    => 'Zend\Mvc\Router\Http\Literal',
                                'options' => [
                                    'route'    => 'edit/:id/',
                                    'defaults' => [
                                        'action'     => 'detail',
                                        'isEditMode' => true,
                                    ],
                                ],
                            ],
                            'add'    => [
                                'type'    => 'Zend\Mvc\Router\Http\Literal',
                                'options' => [
                                    'route'    => 'add/',
                                    'defaults' => [
                                        'action'     => 'detail',
                                        'isEditMode' => false,
                                    ],
                                ],
                            ],
                            'delete' => [
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
