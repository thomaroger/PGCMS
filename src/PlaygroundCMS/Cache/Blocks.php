<?php
/**
* @package : PlaygroundCMS
* @author : troger
* @since : 18/03/2014
*
* Classe qui permet de gérer le cache fichier de objets de type Blocks
**/

namespace PlaygroundCMS\Cache;

use PlaygroundCMS\Service\Block;
use Doctrine\ORM\Internal\Hydration\ObjectHydrator as DoctrineHydrator;

class Blocks extends CachedCollection
{
    /**
    * @var integer CACHE_TIME : Temps de cache fichier pour les blocs
    */
    const CACHE_TIME = 60;
     /**
    * @var Block $blockService : Instance du service de block
    */
    protected $blockService;

    /**
    * getCachedBlocks : Recuperation des blocks cachés
    *
    * @return array $blocks : Blocs qui sont cachés
    */
    public function getCachedBlocks()
    {
        $this->setType('blocks');
        
        return $this->getCachedCollection();
    }

    /**
    * findBlockBySlug : Recuperation d'un bloc en fonction d'un slug
    *
    * @return Block $block: Bloc
    */
    public function findBlockBySlug($slug)
    {
        $blocks = $this->getCachedBlocks();
        if(empty($blocks)){
            return '';
        }

        $slug = (string) $slug;
        $blocks = $blocks['slug'];
        if (empty($blocks[$slug])) {
            return '';
        }

        $block = $blocks[$slug];

        return $block;
    }

    /**
    * findBlockById : Recuperation d'un bloc en fonction d'un id
    *
    * @return Block $block: Bloc
    */
    public function findBlockById($id)
    {
        $blocks = $this->getCachedBlocks();
        if(empty($blocks)){
            return '';
        }
        $id = (integer) $id;
        $blocks = $blocks['id'];
        if (empty($blocks[$id])) {
            return '';
        }

        $block = $blocks[$id];

        return $block;
    }


    /**
    * getCollection : Permet de recuperer les blocs à cacher
    *
    * @return array $collections : blocs à cacher
    */
    protected function getCollection()
    {
        $collections = array();
        $blocks = $this->getBlockService()->getBlockMapper()->findAll();
        foreach ($blocks as $block) {
            $collections['slug'][$block->getSlug()] = $block;
            $collections['id'][$block->getId()] = $block;
        }

        return $collections;
    }

    public function replaceProxyEntity($collection)
    {
        $collection = str_replace('O:57:"DoctrineORMModule\Proxy\__CG__','O:27:"',$collection);

        return $collection;
    }

    /**
     * getBlockService : Getter pour l'instance du Service Block
     *
     * @return Block $blockService
     */
    private function getBlockService()
    {
        if (null === $this->blockService) {
            $this->blockService = $this->getServiceManager()->get('playgroundcms_block_service');
        }

        return $this->blockService;
    }

    /**
     * setTemplateService : Setter pour l'instance du Service Block
     * @param  Block $blockService
     *
     * @return Blocks $blocks
     */
    private function setBlockService($blockService)
    {
        $this->blockService = $blockService;

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