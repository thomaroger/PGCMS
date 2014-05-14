<?php
/**
* @package : PlaygroundCMS
* @author : troger
* @since : 10/04/2014
*
* Classe qui permet de gérer le cache fichier de objets de type LayoutZone
**/

namespace PlaygroundCMS\Cache;

use PlaygroundCMS\Service\LayoutZone;

class LayoutsZones extends CachedCollection
{   
    /**
    * @var integer CACHE_TIME : Temps de cache fichier pour les layoutZones
    */
    const CACHE_TIME = 0;

    /**
    * @var LayoutZone $layoutZoneService : Instance du service de LayoutZone
    */
    protected $layoutZoneService;

    /**
    * getCachedLayoutZone : Recuperation des layoutZones cachés
    *
    * @return array $layoutZones : LayoutZone qui sont cachés
    */
    public function getCachedLayoutZone()
    {
        $this->setType('layoutszones');

        return $this->getCachedCollection();
    }

    /**
    * findLayoutZoneByLayoutAndZone : Recuperation d'une layoutZone en fonction d'un layout et d'une zone
    * @param Layout $layout : Layout
    * @param Zone $zone : Zone
    * 
    * @return LayoutZone $layoutZone : layoutzone
    */
    public function findLayoutZoneByLayoutAndZone($layout, $zone)
    {
        $layoutZones = $this->getCachedLayoutZone();
        $layout = (int) $layout;
        $zone = (int) $zone;
        if (empty($layoutZones[$layout][$zone])) {
            
            return 0;
        }

        return $layoutZones[$layout][$zone];
    }


    /**
    * getCollection : Permet de recuperer les layoutZones à cacher
    *
    * @return array $collections : Templates à cacher
    */
    protected function getCollection()
    {
        $collections = array();
        $layoutZones = $this->getLayoutZoneService()->getLayoutZoneMapper()->findAll();
        foreach ($layoutZones as $layoutZone) {
            $collections[$layoutZone->getLayout()->getId()][$layoutZone->getZone()->getId()] = $layoutZone->getId();
        }

        return $collections;
    }

    /**
     * getLayoutZoneService : Getter pour l'instance du Service LayoutZone
     *
     * @return LayoutZone $layoutZoneService
     */
    private function getLayoutZoneService()
    {
        if (null === $this->layoutZoneService) {
            $this->setLayoutZoneService($this->getServiceManager()->get('playgroundcms_layoutzone_service'));
        }

        return $this->layoutZoneService;
    }

    /**
     * setLayoutZoneService : Setter pour l'instance du Service LayoutZone
     * @param  LayoutZone $layoutZoneService
     *
     * @return LayoutsZones $templates
     */
    private function setLayoutZoneService(LayoutZone $layoutZoneService)
    {
        $this->layoutZoneService = $layoutZoneService;

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