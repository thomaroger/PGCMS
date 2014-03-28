<?php
/**
* @package : PlaygroundCMS
* @author : troger
* @since : 28/03/2014
*
* Classe de service pour l'entite Layout
**/
namespace PlaygroundCMS\Service;

use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\ServiceManager\ServiceManager;
use ZfcBase\EventManager\EventProvider;
use PlaygroundCMS\Mapper\Layout as LayoutMapper;

class Layout extends EventProvider implements ServiceManagerAwareInterface
{

    /**
     * @var PlaygroundCMS\Mapper\Layout layoutMapper
     */
    protected $layoutMapper;

    /**
     * @var Zend\ServiceManager\ServiceManager ServiceManager
     */
    protected $serviceManager;
    
    /**
     * getLayoutMapper : Getter pour layoutMapper
     *
     * @return PlaygroundCMS\Mapper\Layout $layoutMapper
     */
    public function getLayoutMapper()
    {
        if (null === $this->layoutMapper) {
            $this->layoutMapper = $this->getServiceManager()->get('playgroundcms_layout_mapper');
        }

        return $this->layoutMapper;
    }

     /**
     * setLayoutMapper : Setter pour le layoutMapper
     * @param  PlaygroundCMS\Mapper\Layout $layoutMapper
     *
     * @return Layout
     */
    private function setLayoutMapper(LayoutMapper $layoutMapper)
    {
        $this->layoutMapper = $layoutMapper;

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
     * @return Layout
     */
    public function setServiceManager(ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;

        return $this;
    }
}