<?php

namespace PlaygroundCMS;

use Zend\Mvc\MvcEvent;
use Zend\Validator\AbstractValidator;

class Module
{
    protected $eventsArray = array();
    
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
            $translate = $serviceManager->get('viewhelpermanager')->get('translate');
            $translate->getTranslator()->setLocale($locale);  
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
            ),
            'invokables' => array(
                'playgroundcms_block_service' => 'PlaygroundCMS\Service\Block',
                'playgroundcms_block_renderer_service' => 'PlaygroundCMS\Service\BlockRenderer',
                'playgroundcms_blockgenerator_service' => 'PlaygroundCMS\Service\BlockGenerator',
            ),
        );
    }

    public function getViewHelperConfig()
    {
        return array(
            'factories' => array(
                'getBlock' => function ($sm) {
                    $viewHelper = new View\Helper\GetBlock();
                    $viewHelper->setBlockService($sm->getServiceLocator()->get('playgroundcms_block_service'));
                    $viewHelper->setBlockRendererService($sm->getServiceLocator()->get('playgroundcms_block_renderer_service'));
                    return $viewHelper;
                },
            ),
        );
    }
}
