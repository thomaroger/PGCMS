<?php

/**
* @package : PlaygroundCMS
* @author : troger
* @since : 18/03/2013
*
* Configuration pour PlaygroundCMS
**/

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
            /**
            // http://dev.pgcms.fr/fr/article/mon-article-1.html Page entity article
            /*'article' => array(
                'type' => 'PlaygroundCMS\Router\RegexSlash',
                'options' => array(
                  'regex'    => '\/(?<locale>([a-z_]{5}+))\/article\/(?<slugiverse>([\/a-z0-9-]+))-(?<id>([0-9]+)).(?<format>([xml|html|json]+))\/?',
                  'defaults' => array(
                    'controller' => 'PlaygroundCMS\Controller\Front\Article',
                    'action'     => 'index',
                  ),
                  'spec' => '',
                ),
            'may_terminate' => true,
            ),
            */
            /**
            *   @todo : Export de bloc
            */

            // http://dev.pgcms.fr/fr/index-1.html
            'frontend' => array(
                'type' => 'PlaygroundCMS\Router\Http\RegexSlash',
                'options' => array(
                  'regex'    => '\/(?<locale>([a-z_]{5}+))\/(?<slugiverse>([\/a-z0-9-]+))-(?<id>([0-9]+)).(?<format>([xml|html|json]+))\/?',
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
    'translator' => array(
        'locale' => 'fr_FR',
        'translation_file_patterns' => array(
            array(
                'type'         => 'phpArray',
                'base_dir'     => __DIR__ . '/../language',
                'pattern'      => '%s.php',
                'text_domain'  => 'playgroundcms'
            ),
        ),
    ),
);