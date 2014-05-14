<?php
/**
* @package : PlaygroundCMS
* @author : troger
* @since : 10/04/2014
*
* Classe qui permet de gérer le cache fichier de objets de type BlockLayoutZone
**/

namespace PlaygroundCMS\Cache;

use PlaygroundCMS\Service\BlockLayoutZone;

class BlocksLayoutsZones extends CachedCollection
{   
    /**
    * @var integer CACHE_TIME : Temps de cache fichier pour les blockLayoutZone
    */
    const CACHE_TIME = 0;

    /**
    * @var Template $blocklayoutZoneService : Instance du service de blockLayoutZone
    */
    protected $blocklayoutZoneService;

    /**
    * getCachedBlockLayoutZone : Recuperation des templates cachés
    *
    * @return array $blockLayoutZones : blockLayoutZones qui sont cachés
    */
    public function getCachedBlockLayoutZone()
    {
        $this->setType('blockslayoutszones');

        return $this->getCachedCollection();
    }

    /**
    * findBlocksByLayoutZone : Recuperation d'un blockLayoutZone en fonction d'un id de layoutzone
    * @param int $layoutZone : Id du layoutZone
    *
    * @return BlockLayoutZone $blockslayoutsZone
    */
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
    * getCollection : Permet de recuperer les blockLayoutZones à cacher
    *
    * @return array $collections : blockLayoutZones à cacher
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
     * getBlockLayoutZoneService : Getter pour l'instance du Service blockLayoutZone
     *
     * @return BlockLayoutZone $blocklayoutZoneService
     */
    private function getBlockLayoutZoneService()
    {
        if (null === $this->blocklayoutZoneService) {
            $this->setBlockLayoutZoneService($this->getServiceManager()->get('playgroundcms_blocklayoutzone_service'));
        }

        return $this->blocklayoutZoneService;
    }

    /**
     * setTemplateService : Setter pour l'instance du Service blockLayoutZone
     * @param  BlockLayoutZone $blocklayoutZoneService
     *
     * @return BlocksLayoutsZones $blocksLayoutsZones
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