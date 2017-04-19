<?php
// Filename: /module/Blog/config/module.config.php
return array(
    'controllers' => [
        'factories' => [
            'Blog\Controller\List'      => 'Blog\Factory\ListControllerFactory',
            'Blog\Controller\Write'     => 'Blog\Factory\WriteControllerFactory',
            'Blog\Controller\Delete'    => 'Blog\Factory\DeleteControllerFactory',
        ],
    ],
    'service_manager' => [
        'factories' => [
            'Blog\Service\PostServiceInterface' => 'Blog\Factory\PostServiceFactory',
            'Blog\Mapper\PostMapperInterface'   => 'Blog\Factory\ZendDbSqlMapperFactory',
            'Zend\Db\Adapter\Adapter'           => 'Zend\Db\Adapter\AdapterServiceFactory'
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
    // This lines opens the configuration for the RouteManager
    'router' => array(
        // Open configuration for all possible routes
        'routes' => array(
            // Define a new route called "blog"
            'blog' => array(
                // Define the routes type to be "Zend\Mvc\Router\Http\Literal", which is basically just a string
                'type' => 'literal',
                // Configure the route itself
                'options' => array(
                    // Listen to "/blog" as uri
                    'route'    => '/blog',
                    // Define default controller and action to be called when this route is matched
                    'defaults' => array(
                        'controller' => 'Blog\Controller\List',
                        'action'     => 'index',
                    )
                ),
                'may_terminate' => true,
                'child_routes'  => array(
                    'detail' => array(
                        'type'  => 'segment',
                        'options'   => array(
                            'route' => '/:id',
                            'defaults' => array(
                                'action' => 'detail'
                            ),
                            'constraints' => array(
                                'id'    => '[1-9]\d*'
                            ),
                        ),
                    ),
                    'add' => array(
                        'type' => 'literal',
                        'options' => array(
                            'route'    => '/add',
                            'defaults' => array(
                                'controller' => 'Blog\Controller\Write',
                                'action'     => 'add'
                            )
                        )
                    ),
                    'edit' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/edit/:id',
                            'defaults' => array(
                                'controller' => 'Blog\Controller\Write',
                                'action'     => 'edit',
                            ),
                            'contraints'    => array(
                                'id'    => '\d+'
                            ),
                        ),
                    ),
                    'delete' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route'    => '/delete/:id',
                            'defaults' => array(
                                'controller' => 'Blog\Controller\Delete',
                                'action'     => 'index'
                            ),
                            'constraints' => array(
                                'id' => '\d+'
                            )
                        )
                    ),
                ),
            )
        )
    )

);