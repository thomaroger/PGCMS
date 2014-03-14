<?php

namespace PlaygroundCMS;

use Zend\Mvc\MvcEvent;
use Zend\Validator\AbstractValidator;
use Zend\View\Resolver\TemplateMapResolver;

class Module
{

    const DIR_TEMPLATE = '/../../../../../design';

    public function getTemplateFolder()
    {
        /**
            @todo : Default/base since local.php config
                'design' => array(
                    'admin' => array(
                        'package' => 'default',
                        'theme' => 'base',
                    ),
                    'frontend' => array(
                        'package' => 'default',
                        'theme' => 'base',
                    ),
                ),
        **/
        return __DIR__.self::DIR_TEMPLATE.'/frontend/default/base/';
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
            $translate = $serviceManager->get('viewhelpermanager')->get('translate');
            $translate->getTranslator()->setLocale($locale);  
        }

        AbstractValidator::setDefaultTranslator($translator,'playgroundcms');

        $templates = array();
        $templates = $serviceManager->get('playgroundcms_template_mapper')->findAll();
        foreach ($templates as $template) {
            $templatePath = $this->getTemplateFolder().$template->getFile();
            if (!file_exists($templatePath)) {
                throw new \RuntimeException(sprintf('Template not found : "%s"', $template->getName()));
            }

            $templates[$template->getFile()] = $templatePath;
        }
        $resolver = new TemplateMapResolver($templates);
        $serviceManager->get('playgroundcms_module_options')->setTemplateMapResolver($resolver);

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
            ),
            'invokables' => array(
                'playgroundcms_block_service' => 'PlaygroundCMS\Service\Block',
                'playgroundcms_block_renderer' => 'PlaygroundCMS\Renderer\BlockRenderer',
                'playgroundcms_block_generator' => 'PlaygroundCMS\Renderer\BlockGenerator',
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
                    $viewHelper->setBlockRendererService($sm->getServiceLocator()->get('playgroundcms_block_renderer'));
                    return $viewHelper;
                },
            ),
        );
    }
}
