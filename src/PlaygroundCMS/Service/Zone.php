<?php
/**
* @package : PlaygroundCMS
* @author : troger
* @since : 03/04/2014
*
* Classe de service pour l'entite Zone
**/
namespace PlaygroundCMS\Service;

use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\ServiceManager\ServiceManager;
use ZfcBase\EventManager\EventProvider;
use PlaygroundCMS\Mapper\Zone as ZoneMapper;
use PlaygroundCMS\Entity\Zone as ZoneEntity;

class Zone extends EventProvider implements ServiceManagerAwareInterface
{

    /**
     * @var PlaygroundCMS\Mapper\Zone zoneMapper
     */
    protected $zoneMapper;

    /**
     * @var Zend\ServiceManager\ServiceManager ServiceManager
     */
    protected $serviceManager;
    
    /**
    * findByNameOrCreate : Permet de recuperer une zone par son nom
    * @param string $name : Nom de la zone
    *
    * @return Zone $zone
    */
    public function findByNameOrCreate($name)
    {

        $zone = $this->getZoneMapper()->findOneBy(array('name' => $name));
        if (empty($zone)) {
            $zone = new ZoneEntity();
            $zone->setName($name);
            $zone = $this->getZoneMapper()->insert($zone);

            $this->getServiceManager()->get('playgroundcms_feed_service')->createFeed($zone, $zone->getName(), 'New Zone');

        }
         
        return $zone; 
    }

    /**
     * getZoneMapper : Getter pour zoneMapper
     *
     * @return PlaygroundCMS\Mapper\Zone $zoneMapper
     */
    public function getZoneMapper()
    {
        if (null === $this->zoneMapper) {
            $this->zoneMapper = $this->getServiceManager()->get('playgroundcms_zone_mapper');
        }

        return $this->zoneMapper;
    }

     /**
     * setZoneMapper : Setter pour le zoneMapper
     * @param  PlaygroundCMS\Mapper\Zone $zoneMapper
     *
     * @return Zone
     */
    private function setZoneMapper(ZoneMapper $zoneMapper)
    {
        $this->zoneMapper = $zoneMapper;

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
     * @return Zone
     */
    public function setServiceManager(ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;

        return $this;
    }
}