<?php
return array(
    'doctrine' => array(
        'driver' => array(
            'playgroundcms_entity' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => __DIR__ . '/../src/PlaygroundCMS/Entity'
            ),
            
            'orm_default' => array(
                'drivers' => array(
                    'PlaygroundCMS\Entity'  => 'playgroundcms_entity'
                )
            )
        )
    ),
    'router' => array(
        'routes' => array(
            'frontend' => array(
            'type' => 'Zend\Mvc\Router\Http\Literal',
            'options' => array(
              'route' => '/',
              'defaults' => array(
                'controller' => 'PlaygroundCMS\Controller\Index',
                'action'     => 'index',
              ),
            ),
            'may_terminate' => true,
          ),
        ),
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        )
    ),
    'controllers' => array(
        'invokables' => array(
            'PlaygroundCMS\Controller\Index' => 'PlaygroundCMS\Controller\IndexController'
        ),
    ),
    'navigation' => array(
    ),
);