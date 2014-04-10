<?php
/**
* @package : PlaygroundCMS
* @author : troger
* @since : 10/04/2013
*
* Classe qui permet de gérer le cache fichier de objets de type Zones
**/

namespace PlaygroundCMS\Cache;

use PlaygroundCMS\Service\Zone;
use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\ServiceManager\ServiceManager;
use ZfcBase\EventManager\EventProvider;

class Zones extends EventProvider implements ServiceManagerAwareInterface
{   


    /**
    * @var Template $zoneService : Instance du service de zones
    */
    protected $zoneService;
    protected $collections;


    public function findZoneBySlug($zone)
    {
        $collections = $this->getCachedCollection();
        $zone = (string) $zone;
        
        if (empty($collections[$zone])) {
            return '';
        }

        return $collections[$zone];
    }

    /**
    * getCollection : Permet de recuperer les templates à cacher
    *
    * @return array $collections : Templates à cacher
    */
    protected function getCollection()
    {
        $collections = array();
        $zones = $this->getZoneService()->getZoneMapper()->findAll();
        foreach ($zones as $zone) {
            $collections[$zone->getName()] = $zone;
        }
        return $collections;
    }

    /**
     * getTemplateService : Getter pour l'instance du Service Template
     *
     * @return Template $templateService
     */
    private function getZoneService()
    {
        if (null === $this->zoneService) {
            $this->setZoneService($this->getServiceManager()->get('playgroundcms_zone_service'));
        }

        return $this->zoneService;
    }

    /**
     * setTemplateService : Setter pour l'instance du Service Template
     * @param  Template $templateService
     *
     * @return Templates $templates
     */
    private function setZoneService(Zone $zoneService)
    {
        $this->zoneService = $zoneService;

        return $this;
    }

     public function setCachedCollection($collections)
    {
        $this->collections = $collections;

        return $this;
    }

    public function getCachedCollection()
    {
        if($this->collections === null) {
            $this->setCachedCollection($this->getCollection());
        }

        return $this->collections;
    }

    /**
     * getServiceManager : Getter pour l'instance du Service Manager
     *
     * @return ServiceManager $serviceManager
     */
    public function getServiceManager()
    {
        return $this->serviceManager;
    }

    /**
     * setServiceManager : Setter pour l'instance du Service Manager
     * @param  ServiceManager $serviceManager
     *
     * @return CachedCollection $cachedCollection
     */
    public function setServiceManager(ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;

        return $this;
    }
}