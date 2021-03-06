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
                    'Gedmo\Tree\TreeListener',
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

            'export-block' => array(
                'type' => 'PlaygroundCore\Mvc\Router\Http\RegexSlash',
                'options' => array(
                  'regex'    => '\/(?<locale>([a-z_]{5}+))\/export-block\/(?<slug>([a-z0-9-]+))-(?<id>([0-9]+)).(?<format>([xml|html|json]+))\/?',
                  'defaults' => array(
                    'controller' => 'PlaygroundCMS\Controller\Front\ExportBlock',
                    'action'     => 'index',
                  ),
                  'spec' => '',
                ),
            'may_terminate' => true,
            ),
            
            // http://dev.pgcms.fr/fr/index-1.html
            'frontend' => array(
                'type' => 'PlaygroundCMS\Router\Http\RegexSlash',
                'options' => array(
                  'regex'    => '\/(?<locale>([a-z_]{5}+))\/(?<slugiverse>([a-z0-9-]+))-(?<id>([0-9]+)).(?<format>([xml|html|json]+))\/?',
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
                                    'route' => '/page/edit/:id[/revision/:revisionId]',
                                    'defaults' => array(
                                        'controller' => 'PlaygroundCMS\Controller\Back\Page',
                                        'action'     => 'edit',
                                    ),
                                    'constraints' => array(
                                        'id' => '[0-9]+',
                                        'revisionId' => '[0-9]+',
                                    ),
                                ), 
                            ),
                            'page_remove' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/page/remove/:id',
                                    'defaults' => array(
                                        'controller' => 'PlaygroundCMS\Controller\Back\Page',
                                        'action'     => 'remove',
                                    ),
                                    'constraints' => array(
                                        'id' => '[0-9]+',
                                    ),
                                ), 
                            ),
                            'layout' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/layouts[/:filter][/:p]',
                                    'defaults' => array(
                                        'controller' => 'PlaygroundCMS\Controller\Back\Layout',
                                        'action'     => 'list',
                                    ),
                                ),    
                            ),

                            'layout_create' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/layout/create',
                                    'defaults' => array(
                                        'controller' => 'PlaygroundCMS\Controller\Back\Layout',
                                        'action'     => 'create',
                                    ),
                                ),    
                            ),
                            'layout_edit' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/layout/edit/:id',
                                    'defaults' => array(
                                        'controller' => 'PlaygroundCMS\Controller\Back\Layout',
                                        'action'     => 'edit',
                                    ),
                                    'constraints' => array(
                                        'id' => '[0-9]+',
                                    ),
                                ), 
                            ),
                            'layout_remove' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/layout/remove/:id',
                                    'defaults' => array(
                                        'controller' => 'PlaygroundCMS\Controller\Back\Layout',
                                        'action'     => 'remove',
                                    ),
                                    'constraints' => array(
                                        'id' => '[0-9]+',
                                    ),
                                ), 
                            ),
                            'template' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/templates[/:filter][/:p]',
                                    'defaults' => array(
                                        'controller' => 'PlaygroundCMS\Controller\Back\Template',
                                        'action'     => 'list',
                                    ),
                                ),    
                            ),

                            'template_create' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/template/create',
                                    'defaults' => array(
                                        'controller' => 'PlaygroundCMS\Controller\Back\Template',
                                        'action'     => 'create',
                                    ),
                                ),    
                            ),
                            'template_edit' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/template/edit/:id',
                                    'defaults' => array(
                                        'controller' => 'PlaygroundCMS\Controller\Back\Template',
                                        'action'     => 'edit',
                                    ),
                                    'constraints' => array(
                                        'id' => '[0-9]+',
                                    ),
                                ), 
                            ),
                            'template_remove' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/template/remove/:id',
                                    'defaults' => array(
                                        'controller' => 'PlaygroundCMS\Controller\Back\Template',
                                        'action'     => 'remove',
                                    ),
                                    'constraints' => array(
                                        'id' => '[0-9]+',
                                    ),
                                ), 
                            ),
                            'zone' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/zones[/:filter][/:p]',
                                    'defaults' => array(
                                        'controller' => 'PlaygroundCMS\Controller\Back\Zone',
                                        'action'     => 'list',
                                    ),
                                ),    
                            ),
                            'feed' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/feeds[/:filter][/:p]',
                                    'defaults' => array(
                                        'controller' => 'PlaygroundCMS\Controller\Back\Feed',
                                        'action'     => 'list',
                                    ),
                                ),    
                            ),
                            'block' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/blocks[/:filter][/:p]',
                                    'defaults' => array(
                                        'controller' => 'PlaygroundCMS\Controller\Back\Block',
                                        'action'     => 'list',
                                    ),
                                ),    
                            ),

                            'block_create'  => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/block/create/:type',
                                    'defaults' => array(
                                        'controller' => 'PlaygroundCMS\Controller\Back\Block',
                                        'action'     => 'create',
                                    ),
                                    'constraints' => array(
                                        'id' => '[a-Z_]+',
                                    ),
                                ), 
                            ),

                            'block_create_sans_layout'  => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/block/createwithoutlayout/:type',
                                    'defaults' => array(
                                        'controller' => 'PlaygroundCMS\Controller\Back\Block',
                                        'action'     => 'createWithoutLayout',
                                    ),
                                    'constraints' => array(
                                        'id' => '[a-Z_]+',
                                    ),
                                ), 
                            ),

                            'block_edit'  => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/block/edit/:id[/:layoutId]',
                                    'defaults' => array(
                                        'controller' => 'PlaygroundCMS\Controller\Back\Block',
                                        'action'     => 'edit',
                                    ),
                                    'constraints' => array(
                                        'id'       => '[0-9]+',
                                        'layoutId' => '[0-9]+',
                                    ),
                                ), 
                            ),

                            'block_edit_revision'  => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/block/edit/:id[/revision/:revisionId]',
                                    'defaults' => array(
                                        'controller' => 'PlaygroundCMS\Controller\Back\Block',
                                        'action'     => 'edit',
                                    ),
                                    'constraints' => array(
                                        'id'       => '[0-9]+',
                                        'revisionId' => '[0-9]+',
                                    ),
                                ), 
                            ),

                            'block_remove'  => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/block/remove/:id',
                                    'defaults' => array(
                                        'controller' => 'PlaygroundCMS\Controller\Back\Block',
                                        'action'     => 'remove',
                                    ),
                                    'constraints' => array(
                                        'id' => '[0-9]+',
                                    ),
                                ), 
                            ),

                            'blocklayoutzone_edit' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/blocklayoutzone/edit/:id',
                                    'defaults' => array(
                                        'controller' => 'PlaygroundCMS\Controller\Back\Layout',
                                        'action'     => 'blockLayoutZone',
                                    ),
                                    'constraints' => array(
                                        'id' => '[0-9]+',
                                    ),
                                ), 
                            ),
                            'blocklayoutzone_update' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/blocklayoutzone/update/:id/:blocklayoutZoneId/:position',
                                    'defaults' => array(
                                        'controller' => 'PlaygroundCMS\Controller\Back\Layout',
                                        'action'     => 'updateBlockLayoutZone',
                                    ),
                                    'constraints' => array(
                                        'id' => '[0-9]+',
                                        'blocklayoutZoneId' => '[0-9]+',
                                        'position' => '[0-9]+',
                                    ),
                                ), 
                            ),

                            'blocklayoutzone_remove' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/blocklayoutzone/remove/:id/:blocklayoutZoneId',
                                    'defaults' => array(
                                        'controller' => 'PlaygroundCMS\Controller\Back\Layout',
                                        'action'     => 'removeBlockLayoutZone',
                                    ),
                                    'constraints' => array(
                                        'id' => '[0-9]+',
                                        'blocklayoutZoneId' => '[0-9]+',
                                    ),
                                ), 
                            ),

                            'menu' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/menus[/:filter][/:p]',
                                    'defaults' => array(
                                        'controller' => 'PlaygroundCMS\Controller\Back\Menu',
                                        'action'     => 'list',
                                    ),
                                ),    
                            ),

                            'menu_edit' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/menu/edit/:id[/revision/:revisionId]',
                                    'defaults' => array(
                                        'controller' => 'PlaygroundCMS\Controller\Back\Menu',
                                        'action'     => 'edit',
                                    ),
                                    'constraints' => array(
                                        'id' => '[0-9]+',
                                        'revisionId' => '[0-9]+',
                                    )
                                ),    
                            ),

                            'menu_delete' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/menu/delete/:id',
                                    'defaults' => array(
                                        'controller' => 'PlaygroundCMS\Controller\Back\Menu',
                                        'action'     => 'remove',
                                    ),
                                    'constraints' => array(
                                        'id' => '[0-9]+',
                                    )
                                ),    
                            ),
                            'menu_position' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/menu/position',
                                    'defaults' => array(
                                        'controller' => 'PlaygroundCMS\Controller\Back\Menu',
                                        'action'     => 'position',
                                    ),
                                ),    
                            ),

                            'revision' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/revisions[/:filter][/:p]',
                                    'defaults' => array(
                                        'controller' => 'PlaygroundCMS\Controller\Back\Revision',
                                        'action'     => 'list',
                                    ),
                                ),    
                            ),
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
            'PlaygroundCMS\Controller\Front\Page'        => 'PlaygroundCMS\Controller\Front\PageController',
            'PlaygroundCMS\Controller\Front\ExportBlock' => 'PlaygroundCMS\Controller\Front\ExportBlockController',
            
            'PlaygroundCMS\Controller\Back\Dashboard' => 'PlaygroundCMS\Controller\Back\DashboardController',
            'PlaygroundCMS\Controller\Back\Block'     => 'PlaygroundCMS\Controller\Back\BlockController',
            'PlaygroundCMS\Controller\Back\Feed'      => 'PlaygroundCMS\Controller\Back\FeedController',
            'PlaygroundCMS\Controller\Back\Menu'      => 'PlaygroundCMS\Controller\Back\MenuController',
            'PlaygroundCMS\Controller\Back\Page'      => 'PlaygroundCMS\Controller\Back\PageController',
            'PlaygroundCMS\Controller\Back\Layout'    => 'PlaygroundCMS\Controller\Back\LayoutController',
            'PlaygroundCMS\Controller\Back\Revision'  => 'PlaygroundCMS\Controller\Back\RevisionController',
            'PlaygroundCMS\Controller\Back\Template'  => 'PlaygroundCMS\Controller\Back\TemplateController',
            'PlaygroundCMS\Controller\Back\Zone'      => 'PlaygroundCMS\Controller\Back\ZoneController',
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
                    'blocks' => array(
                        'label' => 'Blocks',
                        'route' => 'admin/playgroundcmsadmin/block',
                        'resource' => 'cms',
                        'privilege' => 'admin',
                    ),
                    'layouts' => array(
                        'label' => 'Layouts',
                        'route' => 'admin/playgroundcmsadmin/layout',
                        'resource' => 'cms',
                        'privilege' => 'admin',
                    ),
                    'menus' => array(
                        'label' => 'Menus',
                        'route' => 'admin/playgroundcmsadmin/menu',
                        'resource' => 'cms',
                        'privilege' => 'admin',
                    ),
                    'pages' => array(
                        'label' => 'Pages',
                        'route' => 'admin/playgroundcmsadmin/page',
                        'resource' => 'cms',
                        'privilege' => 'admin',
                    ),
                    'templates' => array(
                        'label' => 'Templates',
                        'route' => 'admin/playgroundcmsadmin/template',
                        'resource' => 'cms',
                        'privilege' => 'admin',
                    ),
                    'zones' => array(
                        'label' => 'Zones',
                        'route' => 'admin/playgroundcmsadmin/zone',
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
    'blocksType' => array(
        'cms' => __DIR__.'/../src/PlaygroundCMS/Blocks', 
    ),

    'feed' => true,
);