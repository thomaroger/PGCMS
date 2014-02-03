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
        ),
    ),
    'navigation' => array(
    ),
);