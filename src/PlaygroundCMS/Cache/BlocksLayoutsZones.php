<?php
/**
* @package : PlaygroundCMS
* @author : troger
* @since : 10/04/2014
*
* Classe qui permet de gérer le cache fichier de objets de type LayoutZone
**/

namespace PlaygroundCMS\Cache;

use PlaygroundCMS\Service\BlockLayoutZone;

class BlocksLayoutsZones extends CachedCollection
{   
    /**
    * @var integer CACHE_TIME : Temps de cache fichier pour les templates
    */
    const CACHE_TIME = 60;

    /**
    * @var Template $templateService : Instance du service de template
    */
    protected $blocklayoutZoneService;

    /**
    * getCachedTemplates : Recuperation des templates cachés
    *
    * @return array $templates : Templates qui sont cachés
    */
    public function getCachedBlockLayoutZone()
    {
        $this->setType('blockslayoutszones');

        return $this->getCachedCollection();
    }


    public function findBlocksByLayoutZone($layoutZone)
    {
        $blockslayoutsZones = $this->getCachedBlockLayoutZone();
        $layoutZone = (int) $layoutZone;

        if (empty($blockslayoutsZones[$layoutZone])) {
            
            return array();
        }

        return $blockslayoutsZones[$layoutZone];
    }


    /**
    * getCollection : Permet de recuperer les templates à cacher
    *
    * @return array $collections : Templates à cacher
    */
    protected function getCollection()
    {
        $collections = array();
        $blockLayoutZones = $this->getBlockLayoutZoneService()->getBlockLayoutZoneMapper()->findByAndOrderBy(array(), array('position' => 'ASC'));
        foreach ($blockLayoutZones as $blockLayoutZone) {
            $collections[$blockLayoutZone->getLayoutZone()->getId()][] = $blockLayoutZone->getBlock()->getSlug();
        }
        
        return $collections;
    }

    /**
     * getTemplateService : Getter pour l'instance du Service Template
     *
     * @return Template $templateService
     */
    private function getBlockLayoutZoneService()
    {
        if (null === $this->blocklayoutZoneService) {
            $this->setBlockLayoutZoneService($this->getServiceManager()->get('playgroundcms_blocklayoutzone_service'));
        }

        return $this->blocklayoutZoneService;
    }

    /**
     * setTemplateService : Setter pour l'instance du Service Template
     * @param  Template $templateService
     *
     * @return Templates $templates
     */
    private function setBlockLayoutZoneService(BlockLayoutZone $blocklayoutZoneService)
    {
        $this->blocklayoutZoneService = $blocklayoutZoneService;

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