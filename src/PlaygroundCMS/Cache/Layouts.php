<?php
/**
* @package : PlaygroundCMS
* @author : troger
* @since : 10/04/2014
*
* Classe qui permet de gérer le cache fichier de objets de type Blocks
**/

namespace PlaygroundCMS\Cache;

use PlaygroundCMS\Service\Block;

class Layouts extends CachedCollection
{
    /**
    * @var integer CACHE_TIME : Temps de cache fichier pour les blocs
    */
    const CACHE_TIME = 0;
     /**
    * @var Block $blockService : Instance du service de block
    */
    protected $layoutService;

    /**
    * getCachedBlocks : Recuperation des blocks cachés
    *
    * @return array $blocks : Blocs qui sont cachés
    */
    public function getCachedLayouts()
    {
        $this->setType('layouts');
        
        return $this->getCachedCollection();
    }

    /**
    * findBlockBySlug : Recuperation d'un bloc en fonction d'un slug
    *
    * @return Block $block: Bloc
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
    * getCollection : Permet de recuperer les blocs à cacher
    *
    * @return array $collections : blocs à cacher
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
     * getBlockService : Getter pour l'instance du Service Block
     *
     * @return Block $blockService
     */
    private function getLayoutService()
    {
        if (null === $this->layoutService) {
            $this->layoutService = $this->getServiceManager()->get('playgroundcms_layout_service');
        }

        return $this->layoutService;
    }

    /**
     * setTemplateService : Setter pour l'instance du Service Block
     * @param  Block $blockService
     *
     * @return Blocks $blocks
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