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


class Zones extends CachedCollection
{

    /**
    * @var integer CACHE_TIME : Temps de cache fichier pour les zones
    */
    const CACHE_TIME = 0;

    /**
    * @var Template $zoneService : Instance du service de zones
    */
    protected $zoneService;

     /**
    * getCachedZones : Recuperation des zones cachés
    *
    * @return array $zones : Zones qui sont cachés
    */
    public function getCachedZones()
    {
        $this->setType('zones');
        
        return $this->getCachedCollection();
    }

    /**
    * findZoneBySlug : Recuperation d'une zone en fonction d'un nom
    * @param string $zoneName : Nom de la zone
    *
    * @return Zone $zone : zone
    */
    public function findZoneBySlug($zoneName)
    {
        $zones = $this->getCachedZones();
        $zoneName = (string) $zoneName;

        if (empty($zones[$zoneName])) {
            return '';
        }
        
        return $zones[$zoneName];
    }

    /**
    * getCollection : Permet de recuperer les zones à cacher
    *
    * @return array $collections : Zones à cacher
    */
    protected function getCollection()
    {
        $collections = array();
        $zones = $this->getZoneService()->getZoneMapper()->findAll();
        foreach ($zones as $zone) {
            // Remove manytoone relation for serialize zone
            $zone->setLayoutzones(array());
            $collections[$zone->getName()] = $zone;
        }

        return $collections;
    }

    /**
     * getZoneService : Getter pour l'instance du Service Zone
     *
     * @return Zone $zoneService
     */
    private function getZoneService()
    {
        if (null === $this->zoneService) {
            $this->setZoneService($this->getServiceManager()->get('playgroundcms_zone_service'));
        }

        return $this->zoneService;
    }

    /**
     * setZoneService : Setter pour l'instance du Service Template
     * @param  Zone $zoneService
     *
     * @return Zones $zones
     */
    private function setZoneService(Zone $zoneService)
    {
        $this->zoneService = $zoneService;

        return $this;
    }

    /** 
    * getCacheTime : Temps de cache du fichier
    *
    * @return int $time
    */
    protected function getCacheTime() {
        $time = self::CACHE_TIME;

        return $time; 
    }
}