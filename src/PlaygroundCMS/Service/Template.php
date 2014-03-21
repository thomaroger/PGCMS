<?php
/**
* @package : PlaygroundCMS
* @author : troger
* @since : 18/03/2013
*
* Classe de service pour l'entite Template
**/
namespace PlaygroundCMS\Service;

use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\ServiceManager\ServiceManager;
use ZfcBase\EventManager\EventProvider;
use PlaygroundCMS\Mapper\Template as TemplateMapper;

class Template extends EventProvider implements ServiceManagerAwareInterface
{

     /**
     * @var PlaygroundCMS\Mapper\Template templateMapper
     */
    protected $templateMapper;

    /**
     * @var Zend\ServiceManager\ServiceManage ServiceManager
     */
    protected $serviceManager;

     /**
     * getTemplateMapper : Getter pour templateMapper
     *
     * @return PlaygroundCMS\Mapper\Template $templateMapper
     */
    public function getTemplateMapper()
    {
        if (null === $this->templateMapper) {
            $this->templateMapper = $this->getServiceManager()->get('playgroundcms_template_mapper');
        }

        return $this->templateMapper;
    }

      /**
     * setTemplateMapper : Setter pour le templateMapper
     *
     * @param  TemplateMapper $templateMapper
     * @return Template
     */
    public function setTemplateMapper(TemplateMapper $templateMapper)
    {
        $this->templateMapper = $templateMapper;

        return $this;
    }

     /**
     * getServiceManager : Getter pour serviceManager
     *
     * @return ServiceManager
     */
    public function getServiceManager()
    {
        return $this->serviceManager;
    }

    /**
     * setServiceManager : Setter pour le serviceManager
     * @param  ServiceManager $serviceManager
     *
     * @return Template
     */
    public function setServiceManager(ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;

        return $this;
    }
}