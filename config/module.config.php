<?php
return array(
    'doctrine' => array(
        'eventmanager' => array(
            'orm_default' => array(
                'subscribers' => array(
                    'Gedmo\Translatable\TranslatableListener',
                ),
            ),
        ),
        'driver' => array(
            'playgroundcms_entity' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => __DIR__ . '/../src/PlaygroundCMS/Entity'
            ),
            'translatable_entities' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../src/PlaygroundCMS/Entity/Translation')
            ),
            
            'orm_default' => array(
                'drivers' => array(
                    'PlaygroundCMS\Entity'  => 'playgroundcms_entity',
                    'Gedmo\Translatable\Entity' => 'translatable_entities'
                )
            )
        )
    ),
    'data-fixture' => array(
        'pgcms_fixtures' => __DIR__ . '/../src/PlaygroundCMS/DataFixtures/ORM',
    ),
    'router' => array(
        'routes' => array(
            'frontend' => array(
            'type' => 'Zend\Mvc\Router\Http\Literal',
            'options' => array(
              'route' => '/',
              'defaults' => array(
                'controller' => 'PlaygroundCMS\Controller\Front\Page',
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
            'PlaygroundCMS\Controller\Front\Page' => 'PlaygroundCMS\Controller\Front\PageController'
        ),
    ),
    'navigation' => array(
    ),
);