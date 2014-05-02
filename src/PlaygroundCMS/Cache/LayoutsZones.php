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
    * @var integer CACHE_TIME : Temps de cache fichier pour les templates
    */
    const CACHE_TIME = 0;

    /**
    * @var Template $templateService : Instance du service de template
    */
    protected $layoutZoneService;

    /**
    * getCachedTemplates : Recuperation des templates cachés
    *
    * @return array $templates : Templates qui sont cachés
    */
    public function getCachedLayoutZone()
    {
        $this->setType('layoutszones');

        return $this->getCachedCollection();
    }


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
    * getCollection : Permet de recuperer les templates à cacher
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
     * getTemplateService : Getter pour l'instance du Service Template
     *
     * @return Template $templateService
     */
    private function getLayoutZoneService()
    {
        if (null === $this->layoutZoneService) {
            $this->setLayoutZoneService($this->getServiceManager()->get('playgroundcms_layoutzone_service'));
        }

        return $this->layoutZoneService;
    }

    /**
     * setTemplateService : Setter pour l'instance du Service Template
     * @param  Template $templateService
     *
     * @return Templates $templates
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