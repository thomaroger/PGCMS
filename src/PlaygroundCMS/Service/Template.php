<?php

namespace PlaygroundCMS\Service;

use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\ServiceManager\ServiceManager;
use ZfcBase\EventManager\EventProvider;

class Template extends EventProvider implements ServiceManagerAwareInterface
{


    /**
     * @var contactMapper
     */
    protected $templateMapper;

    /**
     * @var ServiceManager
     */
    protected $serviceManager;

  
   
    public function getTemplateMapper()
    {
        if (null === $this->templateMapper) {
            $this->templateMapper = $this->getServiceManager()->get('playgroundcms_template_mapper');
        }

        return $this->templateMapper;
    }

    public function setTemplateMapper($templateMapper)
    {
        $this->templateMapper = $templateMapper;

        return $this;
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