<?php
/**
* @package : PlaygroundCMS
* @author : troger
* @since : 03/04/2014
*
* Classe de service pour l'entite LayoutZone
**/
namespace PlaygroundCMS\Service;

use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\ServiceManager\ServiceManager;
use ZfcBase\EventManager\EventProvider;
use PlaygroundCMS\Mapper\LayoutZone as LayoutZoneMapper;
use PlaygroundCMS\Entity\LayoutZone as layoutZoneEntity;

class LayoutZone extends EventProvider implements ServiceManagerAwareInterface
{

    /**
     * @var PlaygroundCMS\Mapper\LayoutZone layoutZoneMapper
     */
    protected $layoutZoneMapper;

    /**
     * @var Zend\ServiceManager\ServiceManager ServiceManager
     */
    protected $serviceManager;


    public function findByLayoutZoneOrCreate($layout, $zone)
    {
        $layoutZone = $this->getLayoutZoneMapper()->findOneBy(array('layout' => $layout, 'zone' => $zone));
        if (empty($readEvents)) {
            $layoutZone = new layoutZoneEntity();
            $layoutZone->setLayout($layout);
            $layoutZone->setZone($zone);
            $layoutZone = $this->getLayoutZoneMapper()->insert($layoutZone);
        }
        
        return $layoutZone;
    }
    
    /**
     * getLayoutZoneMapper : Getter pour layoutZoneMapper
     *
     * @return PlaygroundCMS\Mapper\LayoutZone $layoutZoneMapper
     */
    public function getLayoutZoneMapper()
    {
        if (null === $this->layoutZoneMapper) {
            $this->layoutZoneMapper = $this->getServiceManager()->get('playgroundcms_layoutZone_mapper');
        }

        return $this->layoutZoneMapper;
    }

     /**
     * setLayoutZoneMapper : Setter pour le layoutZoneMapper
     * @param  PlaygroundCMS\Mapper\LayoutZone $layoutZoneMapper
     *
     * @return LayoutZone
     */
    private function setLayoutZoneMapper(LayoutZoneMapper $layoutZoneMapper)
    {
        $this->layoutZoneMapper = $layoutZoneMapper;

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
     * @return LayoutZone
     */
    public function setServiceManager(ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;

        return $this;
    }
}