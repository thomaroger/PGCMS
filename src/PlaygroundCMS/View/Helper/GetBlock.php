<?php
/**
* @package : PlaygroundCMS
* @author : troger
* @since : 18/03/2014
*
* Helper pour render un bloc
**/
namespace PlaygroundCMS\View\Helper;

use Zend\View\Helper\AbstractHelper;
use PlaygroundCMS\Entity\Block;
use PlaygroundCMS\Cache\Blocks;
use PlaygroundCMS\Renderer\BlockRenderer;

class GetBlock extends AbstractHelper
{

    protected $blockRenderer;

    protected $blockService;

    /**
     * __invoke : permet de rendre un bloc
     * @param  string $slug slug
     *
     * @return string $return 
     */
    public function __invoke($slug)
    {

        $block = $this->getBlockFromCache((string) $slug);
        
        if ($block instanceof Block) {
            echo $this->getBlockRendererService()
                        ->setBlock($block)
                        ->render();
        }

        echo '';
    }

    /**
     * getBlockFromCache : permet de recuperer un bloc du cache
     * @param  string $slug slug
     *
     * @return Block $block 
     */
    private function getBlockFromCache($slug)
    {
        $block = $this->getCachedBlocks()->findBlockBySlug($slug);

        return $block;
    }

    /**
    * getBlockService : Getter pour blockService
    *
    * @return PlaygroundCMS\Cache\Blocks $blockService
    */
    private function getCachedBlocks()
    {
        if (null === $this->blockService) {
            $this->blockService = $this->getServiceManager()->get('playgroundcms_cached_blocks');
        }

        return $this->blockService;
    }

    /**
    * setBlockService : Setter pour blockService
    * @param PlaygroundCMS\Cache\Blocks $blockService
    *
    * @return GetBlock 
    */
    public function setCachedBlocks(Blocks $blockService)
    {
        $this->blockService = $blockService;

        return $this;
    }

    /**
    * getBlockRendererService : Getter pour blockRenderer
    *
    * @return PlaygroundCMS\Renderer BlockRenderer $blockRenderer
    */
    private function getBlockRendererService()
    {
        if (null === $this->blockRenderer) {
            $this->blockRenderer = $this->getServiceManager()->get('playgroundcms_block_renderer');
        }

        return $this->blockRenderer;
    }

    /**
    * setBlockRendererService : Setter pour blockRenderer
    * @param PlaygroundCMS\Renderer BlockRenderer $blockRenderer
    *
    * @return GetBlock 
    */
    public function setBlockRendererService(BlockRenderer $blockRenderer)
    {
        $this->blockRenderer = $blockRenderer;

        return $this;
    }
}