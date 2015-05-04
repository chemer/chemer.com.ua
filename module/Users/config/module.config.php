<?php
return array(
    'router' => array(
        'routes' => array(
            'user' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/user',
                    'defaults' => array(
                        'controller' => 'user_controller',
                        'action'     => 'setting',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'login' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/login',
                            'defaults' => array(
                                'controller' => 'user_controller',
                                'action'     => 'login',
                            ),
                        ),
                    ),
                    'register' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/register',
                            'defaults' => array(
                                'controller' => 'user_controller',
                                'action'     => 'register',
                            ),
                        ),
                    ),
                    'register_confirm' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/register_confirm[/:id[/:code]]',
                            'constraints' => array(
                                'id'      => '[0-9]+',
                                'code'    => '.*',
                            ),
                            'defaults' => array(
                                'controller' => 'user_controller',
                                'action'     => 'register_confirm',
                                'id'      => 0,
                                'code'    => 0,
                            ),
                        ),
                    ),
                    'setting' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/setting',
                            'defaults' => array(
                                'controller' => 'user_controller',
                                'action'     => 'setting',
                            ),
                        ),
                    ),
                    'logout' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/logout',
                            'defaults' => array(
                                'controller' => 'user_controller',
                                'action'     => 'logout',
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'users' => __DIR__ . '/../view',
        ),
        'strategies' => array(
            'ViewJsonStrategy',
        ),
    ),
);
