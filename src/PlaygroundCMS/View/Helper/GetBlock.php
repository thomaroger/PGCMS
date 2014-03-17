<?php

namespace PlaygroundCMS\View\Helper;

use Zend\View\Helper\AbstractHelper;
use PlaygroundCMS\Entity\Block;

class GetBlock extends AbstractHelper
{

    protected $blockRenderer;

    protected $blockService;

    /**
     * __invoke
     *
     * @access public
     * @param  array  $options array of options
     * @return string
     */
    public function __invoke($slug)
    {

        $block = $this->getBlockFromCache($slug);
        
        if ($block instanceof Block) {
            echo $this->getBlockRendererService()
                        ->setBlock($block)
                        ->render();
        }

        echo '';
    }

    public function getBlockFromCache($slug)
    {
        $block = $this->getBlockService()->findBlockBySlug($slug);

        return $block;
    }


    public function getBlockService()
    {
        if (null === $this->blockService) {
            $this->blockService = $this->getServiceManager()->get('playgroundcms_cached_blocks');
        }

        return $this->blockService;
    }

    public function setBlockService($blockService)
    {
        $this->blockService = $blockService;

        return $this;
    }

    public function getBlockRendererService()
    {
        if (null === $this->blockRenderer) {
            $this->blockRenderer = $this->getServiceManager()->get('playgroundcms_blockrenderer_service');
        }

        return $this->blockRenderer;
    }

    public function setBlockRendererService($blockRenderer)
    {
        $this->blockRenderer = $blockRenderer;

        return $this;
    }
}