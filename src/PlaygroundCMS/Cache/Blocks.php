<?php

namespace PlaygroundCMS\Cache;

class Blocks extends CachedCollection
{
    protected $blockService;

    public function getCachedBlocks()
    {
        $this->setType('blocks');
        
        return $this->getCachedCollection();
    }

    public function findBlockBySlug($slug)
    {
        $blocks = $this->getCachedBlocks();

        if (empty($blocks[$slug])) {
            return '';
        }

        return $blocks[$slug];
    }

    public function getCollection()
    {
        $collections = array();
        $blocks = $this->getBlockService()->getBlockMapper()->findAll();
        foreach ($blocks as $block) {
            $collections[$block->getSlug()] = $block;
        }

        return $collections;
    }

    public function getBlockService()
    {
        if (null === $this->blockService) {
            $this->blockService = $this->getServiceManager()->get('playgroundcms_block_service');
        }

        return $this->blockService;
    }

    public function setBlockService($blockService)
    {
        $this->blockService = $blockService;

        return $this;
    }

}