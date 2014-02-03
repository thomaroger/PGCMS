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
            ),
            'invokables' => array(
            ),
        );
    }
}
