<?php

namespace PlaygroundCMS\Renderer;

use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\ServiceManager\ServiceManager;
use ZfcBase\EventManager\EventProvider;

class BlockGenerator extends EventProvider implements ServiceManagerAwareInterface
{
   

    public function generate($serviceManager, $block, $format = 'html', $parameters = array())
    {
        $controllerType = $block->getType();
        $controller = new $controllerType($serviceManager, $block);

        return $controller->renderAction($format, $parameters);
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