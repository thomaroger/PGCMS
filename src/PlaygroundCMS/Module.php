<?php

namespace PlaygroundCMS;

use Zend\Mvc\MvcEvent;
use Zend\Validator\AbstractValidator;
use Zend\View\Resolver\TemplateMapResolver;

class Module
{
    const DIR_TEMPLATE = '/../../../../../design';

    public function getTemplateFolder($serviceManager)
    {
        $config = $serviceManager->get('Config');
        
        return __DIR__.self::DIR_TEMPLATE.'/frontend/'.$config['design']['frontend']['package'].'/'.$config['design']['frontend']['theme'].'/';
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
            $options = $serviceManager->get('playgroundcore_module_options');

            $locale = $options->getLocale();

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
                'playgroundcms_module_options' => function  ($sm) {
                    $config = $sm->get('Configuration');

                    return new Options\ModuleOptions(isset($config['playgroundcms']) ? $config['playgroundcms'] : array());
                },
                
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

                'RoutePluginManager' => function ($sm) { 
                    return new Router\RoutePluginManager();
                },
            ),
            'invokables' => array(
                'playgroundcms_block_service'     => 'PlaygroundCMS\Service\Block',
                'playgroundcms_template_service'  => 'PlaygroundCMS\Service\Template',
                'playgroundcms_ressource_service' => 'PlaygroundCMS\Service\Ressource',
                'playgroundcms_page_service'      => 'PlaygroundCMS\Service\Page',
                'playgroundcms_layout_service'      => 'PlaygroundCMS\Service\Layout',

                'playgroundcms_block_renderer'    => 'PlaygroundCMS\Renderer\BlockRenderer',
                'playgroundcms_block_generator'   => 'PlaygroundCMS\Renderer\BlockGenerator',

                'playgroundcms_cached_blocks'     => 'PlaygroundCMS\Cache\Blocks',
                'playgroundcms_cached_ressources' => 'PlaygroundCMS\Cache\Ressources',
                'playgroundcms_cached_templates'  => 'PlaygroundCMS\Cache\Templates',
            ),
        );
    }

    public function getViewHelperConfig()
    {
        return array(
            'factories' => array(
                'getBlock' => function ($sm) {
                    $viewHelper = new View\Helper\GetBlock();
                    $viewHelper->setBlockService($sm->getServiceLocator()->get('playgroundcms_cached_blocks'));
                    $viewHelper->setBlockRendererService($sm->getServiceLocator()->get('playgroundcms_block_renderer'));
                    return $viewHelper;
                },

                'cmsTranslate' => function($sm){
                    $viewHelper = new View\Helper\CMSTranslate();
                    $viewHelper->setServiceLocator($sm->getServiceLocator());
                    return $viewHelper;
                }
            ),
        );
    }
}
