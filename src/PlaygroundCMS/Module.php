<?php

namespace PlaygroundCMS;

use Zend\Mvc\MvcEvent;
use Zend\Validator\AbstractValidator;
use Zend\View\Resolver\TemplateMapResolver;

class Module
{

    /**
    * getTemplateFolder : Permet de recuperer le repertoire ou se trouve les templates
    * @param ServiceManager $serviceManager
    *
    * @return string $path
    */
    public function getTemplateFolder($serviceManager)
    {

        return $serviceManager->get('playgroundcms_module_options')->getTemplateFolder($serviceManager);        
    }

    
    public function onBootstrap(MvcEvent $e)
    {
        $application     = $e->getTarget();
        $serviceManager  = $application->getServiceManager();
        $eventManager    = $application->getEventManager();

        $translator = $serviceManager->get('translator');

        // Gestion de la locale
        if (PHP_SAPI !== 'cli') {
            $locale = null;

            // Recuperation de la locale depuis l'url
            $router = $serviceManager->get('router');
            $request = $serviceManager->get('request');
            $routeMatch = $router->match($request);
            if($routeMatch){
                $locale = trim($routeMatch->getParam('locale'), '/');  
            }

            if(empty($locale)){
                $options = $serviceManager->get('playgroundcore_module_options');
                $locale = $options->getLocale();
            }


            $translator->setLocale($locale);

            // plugins
            $pluginTranslator = $serviceManager->get('viewhelpermanager')->get('translate')->getTranslator();
            $pluginTranslator->setLocale($locale);  
            $serviceManager->get('playgroundcms_module_options')->setTranslator($pluginTranslator);

            // Gestion des templates via TemplateMapResolver
            $templates = array();
            $templates = $serviceManager->get('playgroundcms_cached_templates')->getCachedTemplates();
            foreach ($templates as $template) {
                $templatePath = $this->getTemplateFolder($serviceManager).$template->getFile();
                if (!file_exists($templatePath)) {
                    throw new \RuntimeException(sprintf('Template not found : "%s"', $template->getName()));
                }

                $templates[$template->getFile()] = $templatePath;
            }
            $resolver = new TemplateMapResolver($templates);
            $serviceManager->get('playgroundcms_module_options')->setTemplateMapResolver($resolver);
            $serviceManager->get('playgroundcms_module_options')->setThemeFolder($this->getTemplateFolder($serviceManager));
        }
        
        AbstractValidator::setDefaultTranslator($translator,'playgroundcms');
    }

    public function getConfig()
    {
        return include __DIR__ . '/../../config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/../../src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getServiceConfig()
    {
        return array(
            'aliases' => array(
                'playgroundcms_doctrine_em' => 'doctrine.entitymanager.orm_default',
            ),    
            'factories' => array(
                // OPTION 
                'playgroundcms_module_options' => function  ($sm) {
                    $config = $sm->get('Configuration');

                    return new Options\ModuleOptions(isset($config['playgroundcms']) ? $config['playgroundcms'] : array());
                },

                // MAPPER
                'playgroundcms_block_mapper' => function  ($sm) {
                    return new Mapper\Block($sm->get('playgroundcms_doctrine_em'), $sm->get('playgroundcms_module_options'));
                },

                'playgroundcms_template_mapper' => function  ($sm) {
                    return new Mapper\Template($sm->get('playgroundcms_doctrine_em'), $sm->get('playgroundcms_module_options'));
                },

                'playgroundcms_ressource_mapper' => function  ($sm) {
                    return new Mapper\Ressource($sm->get('playgroundcms_doctrine_em'), $sm->get('playgroundcms_module_options'));
                },

                'playgroundcms_page_mapper' => function  ($sm) {
                    return new Mapper\Page($sm->get('playgroundcms_doctrine_em'), $sm->get('playgroundcms_module_options'));
                },

                'playgroundcms_layout_mapper' => function  ($sm) {
                    return new Mapper\Layout($sm->get('playgroundcms_doctrine_em'), $sm->get('playgroundcms_module_options'));
                },

                'playgroundcms_zone_mapper' => function  ($sm) {
                    return new Mapper\Zone($sm->get('playgroundcms_doctrine_em'), $sm->get('playgroundcms_module_options'));
                },

                'playgroundcms_layoutZone_mapper' => function  ($sm) {
                    return new Mapper\LayoutZone($sm->get('playgroundcms_doctrine_em'), $sm->get('playgroundcms_module_options'));
                },

                'playgroundcms_blocklayoutZone_mapper' => function  ($sm) {
                    return new Mapper\BlockLayoutZone($sm->get('playgroundcms_doctrine_em'), $sm->get('playgroundcms_module_options'));
                },

                'playgroundcms_menu_mapper' => function  ($sm) {
                    return new Mapper\Menu($sm->get('playgroundcms_doctrine_em'), $sm->get('playgroundcms_module_options'));
                },

                'playgroundcms_revision_mapper' => function  ($sm) {
                    return new Mapper\Revision($sm->get('playgroundcms_doctrine_em'), $sm->get('playgroundcms_module_options'));
                },

                // ROUTER
                'RoutePluginManager' => function ($sm) { 
                    return new Router\RoutePluginManager();
                },


                // FORM
                'playgroundcms-blocks-freehtml-form' => function  ($sm) {
                    $form = new Form\FreeHTMLForm(null, $sm);

                    return $form;
                },

                'playgroundcms-blocks-blocklist-form' => function  ($sm) {
                    $form = new Form\BlockListForm(null, $sm);

                    return $form;
                },

                'playgroundcms-blocks-switchlocale-form' => function  ($sm) {
                    $form = new Form\SwitchLocaleForm(null, $sm);

                    return $form;
                },

                'playgroundcms-blocks-menu-form' => function  ($sm) {
                    $form = new Form\MenuForm(null, $sm);

                    return $form;
                },
            ),
            'invokables' => array(
                'playgroundcms_block_service'           => 'PlaygroundCMS\Service\Block',
                'playgroundcms_template_service'        => 'PlaygroundCMS\Service\Template',
                'playgroundcms_ressource_service'       => 'PlaygroundCMS\Service\Ressource',
                'playgroundcms_page_service'            => 'PlaygroundCMS\Service\Page',
                'playgroundcms_layout_service'          => 'PlaygroundCMS\Service\Layout',
                'playgroundcms_zone_service'            => 'PlaygroundCMS\Service\Zone',
                'playgroundcms_layoutZone_service'      => 'PlaygroundCMS\Service\LayoutZone',
                'playgroundcms_blocklayoutZone_service' => 'PlaygroundCMS\Service\BlockLayoutZone',
                'playgroundcms_feed_service'            => 'PlaygroundCMS\Service\Feed',
                'playgroundcms_menu_service'            => 'PlaygroundCMS\Service\Menu',
                'playgroundcms_revision_service'            => 'PlaygroundCMS\Service\Revision',

                'playgroundcms_block_renderer'  => 'PlaygroundCMS\Renderer\BlockRenderer',
                'playgroundcms_zone_renderer'   => 'PlaygroundCMS\Renderer\ZoneRenderer',
                'playgroundcms_block_generator' => 'PlaygroundCMS\Renderer\BlockGenerator',

                'playgroundcms_cached_blocks'             => 'PlaygroundCMS\Cache\Blocks',
                'playgroundcms_cached_ressources'         => 'PlaygroundCMS\Cache\Ressources',
                'playgroundcms_cached_templates'          => 'PlaygroundCMS\Cache\Templates',
                'playgroundcms_cached_layouts'            => 'PlaygroundCMS\Cache\Layouts',
                'playgroundcms_cached_layoutszones'       => 'PlaygroundCMS\Cache\LayoutsZones',
                'playgroundcms_cached_blockslayoutszones' => 'PlaygroundCMS\Cache\BlocksLayoutsZones',
                'playgroundcms_cached_zones'              => 'PlaygroundCMS\Cache\Zones',
            ),
        );
    }

    public function getViewHelperConfig()
    {
        return array(
            'factories' => array(
                'getBlock' => function ($sm) {
                    $viewHelper = new View\Helper\GetBlock();
                    $viewHelper->setCachedBlocks($sm->getServiceLocator()->get('playgroundcms_cached_blocks'));
                    $viewHelper->setBlockRendererService($sm->getServiceLocator()->get('playgroundcms_block_renderer'));
                    return $viewHelper;
                },

                'getZone' => function ($sm) {
                    $viewHelper = new View\Helper\GetZone();
                    $viewHelper->setCachedZones($sm->getServiceLocator()->get('playgroundcms_cached_zones'));
                    $viewHelper->setZoneRendererService($sm->getServiceLocator()->get('playgroundcms_zone_renderer'));
                    return $viewHelper;
                },

                'cmsTranslate' => function($sm){
                    $viewHelper = new View\Helper\CMSTranslate();
                    $viewHelper->setServiceLocator($sm->getServiceLocator());
                    return $viewHelper;
                },

                'getUrl' => function ($sm) {
                    $viewHelper = new View\Helper\GetUrl();
                    $viewHelper->setRessourceService($sm->getServiceLocator()->get('playgroundcms_ressource_service'));
                    return $viewHelper;
                },
            ),
        );
    }
}
