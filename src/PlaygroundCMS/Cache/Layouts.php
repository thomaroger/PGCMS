<?php
/**
* @package : PlaygroundCMS
* @author : troger
* @since : 10/04/2014
*
* Classe qui permet de gérer le cache fichier de objets de type layouts
**/

namespace PlaygroundCMS\Cache;

use PlaygroundCMS\Service\Block;

class Layouts extends CachedCollection
{
    /**
    * @var integer CACHE_TIME : Temps de cache fichier pour les layouts
    */
    const CACHE_TIME = 0;
     /**
    * @var Layout $layoutService : Instance du service de layout
    */
    protected $layoutService;

    /**
    * getCachedLayouts : Recuperation des layouts cachés
    *
    * @return array $layouts : Layouts qui sont cachés
    */
    public function getCachedLayouts()
    {
        $this->setType('layouts');
        
        return $this->getCachedCollection();
    }

    /**
    * findLayoutByFile : Recuperation d'un layout en fonction d'un fichier
    * @param string $file : fichier du layout
    *
    * @return Layout $layout : Layout
    */
    public function findLayoutByFile($file)
    {
        $layouts = $this->getCachedLayouts();
        $file = (string) $file;

        if (empty($layouts[$file])) {
            return '';
        }

        return $layouts[$file];
    }

    /**
    * getCollection : Permet de recuperer les layouts à cacher
    *
    * @return array $collections : layouts à cacher
    */
    protected function getCollection()
    {
        $collections = array();
        $layouts = $this->getLayoutService()->getLayoutMapper()->findAll();
        foreach ($layouts as $layout) {
            $collections[$layout->getFile()] = $layout->getId();
        }

        return $collections;
    }

    /**
     * getLayoutService : Getter pour l'instance du Service Layout
     *
     * @return Layout $layoutService
     */
    private function getLayoutService()
    {
        if (null === $this->layoutService) {
            $this->layoutService = $this->getServiceManager()->get('playgroundcms_layout_service');
        }

        return $this->layoutService;
    }

    /**
     * setLayoutService : Setter pour l'instance du Service Layout
     * @param  Layout $layoutService
     *
     * @return Layouts $layouts
     */
    private function setLayoutService($layoutService)
    {
        $this->layoutService = $layoutService;

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