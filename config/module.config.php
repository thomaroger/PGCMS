<?php

/**
* @package : PlaygroundCMS
* @author : troger
* @since : 18/03/2014
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
            'userLogout' => array(
                'type' => 'Literal',
                'options' => array(
                  'route'    => '/mon-compte/logout',
                  'defaults' => array(
                        'controller' => 'playgrounduser_user',
                        'action'     => 'logout',
                    ),
                ),
            'may_terminate' => true,
            ),
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
            'admin' => array(
                'child_routes' => array(
                    'playgroundcmsadmin' => array(
                        'type' => 'Literal',
                        'priority' => 1000,
                        'options' => array(
                            'route' => '/playgroundcms',
                            'defaults' => array(
                                'controller' => 'PlaygroundCMS\Controller\Back\Dashboard',
                                'action' => 'index',
                            ),
                        ),
                        'may_terminate' => true,
                        'child_routes' => array(
                            'page' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/pages[/:filter][/:p]',
                                    'defaults' => array(
                                        'controller' => 'PlaygroundCMS\Controller\Back\Page',
                                        'action'     => 'list',
                                    ),
                                ),    
                            ),
                            'page_create' => array(
                                'type' => 'Literal',
                                'options' => array(
                                    'route' => '/page/create',
                                    'defaults' => array(
                                        'controller' => 'PlaygroundCMS\Controller\Back\Page',
                                        'action'     => 'create',
                                    ),
                                ), 
                            ),
                            'page_edit' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/page/edit/:id',
                                    'defaults' => array(
                                        'controller' => 'PlaygroundCMS\Controller\Back\Page',
                                        'action'     => 'edit',
                                    ),
                                    'constraints' => array(
                                        'id' => '[0-9]+',
                                    ),
                                ), 
                            )
                        ),
                    ),
                ),
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
            'PlaygroundCMS\Controller\Front\Page' => 'PlaygroundCMS\Controller\Front\PageController',

            'PlaygroundCMS\Controller\Back\Dashboard' => 'PlaygroundCMS\Controller\Back\DashboardController',
            'PlaygroundCMS\Controller\Back\Page' => 'PlaygroundCMS\Controller\Back\PageController'
        ),
    ),
    'navigation' => array(
        'admin' => array(
            'playgroundcms' => array(
                'label' => 'CMS',
                'route' => 'admin/playgroundcmsadmin',
                'resource' => 'cms',
                'privilege' => 'admin',
                'pages' => array(
                    'list' => array(
                        'label' => 'Gestion des pages',
                        'route' => 'admin/playgroundcmsadmin/page',
                        'resource' => 'cms',
                        'privilege' => 'admin',
                    ),
                ),
            ),
        ),
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