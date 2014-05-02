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
    * getCachedBlocks : Recuperation des blocks cachés
    *
    * @return array $blocks : Blocs qui sont cachés
    */
    public function getCachedZones()
    {
        $this->setType('zones');
        
        return $this->getCachedCollection();
    }


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
    * getCollection : Permet de recuperer les templates à cacher
    *
    * @return array $collections : Templates à cacher
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