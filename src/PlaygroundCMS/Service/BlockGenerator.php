<?php

namespace PlaygroundCms\Service;

use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\ServiceManager\ServiceManager;
use ZfcBase\EventManager\EventProvider;

class BlockGenerator extends EventProvider implements ServiceManagerAwareInterface
{
   

    public function generate($block, $format = 'html', $parameters = array())
    {
        $controllerType = $block->getType();
        $controller = new $controllerType($block);

        return $controller->renderAction($block, $format, $parameters);
    }

   
    /**
     * Retrieve service manager instance
     *
     * @return ServiceManager
     */
    public function getServiceManager()
    {
        return $this->serviceManager;
    }

    /**
     * Set service manager instance
     *
     * @param  ServiceManager $serviceManager
     * @return User
     */
    public function setServiceManager(ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;

        return $this;
    }
}