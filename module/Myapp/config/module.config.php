<?php

return array(
    'controllers' => array(
        'invokables' => array(
            'Myapp\Controller\Myapp' => 'Myapp\Controller\MyappController',
        ),
    ),
    

    'router' => array(
        'routes' => array(
            'myapp' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/myapp[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Myapp\Controller\Myapp',
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
    ),

    
    'view_manager' => array(
        'template_path_stack' => array(
            'myapp' => __DIR__ . '/../view',
        ),
    ),
);
