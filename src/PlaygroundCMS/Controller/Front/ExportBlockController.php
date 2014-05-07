<?php
/**
* @package : PlaygroundCMS
* @author : troger
* @since : 13/04/2014
*
* Classe de controleur qui permet d'exporter un block
**/
namespace PlaygroundCMS\Controller\Front;

use Zend\Mvc\Controller\AbstractActionController;
use PlaygroundCMS\Entity\Block;
use PlaygroundCMS\Cache\Blocks;
use PlaygroundCMS\Renderer\BlockRenderer;

class ExportBlockController extends AbstractActionController
{
    /**
    * @var BlockRenderer $blockRenderer : Renderer de bloc
    */
    protected $blockRenderer;

    /**
    * @var Block $blockService Service du bloc
    */
    protected $blockService;

    /**
    * indexAction : Action index du controller de page
    *
    * @return Response $response 
    */
    public function indexAction()
    {
        $response = $this->getResponse();
        $id = $this->getEvent()->getRouteMatch()->getParam('id', '0');
        $slug = $this->getEvent()->getRouteMatch()->getParam('slug', '');
        
        $block = $this->getBlockFromCache((string) $slug);

        if (!($block instanceof Block)) {
            $response->setStatusCode(404);
            
            return ; 
        }

        if($block->getId() != $id) {
            $response->setStatusCode(404);
            
            return ;   
        }

        if($block->getIsExportable() != $id) {
            $response->setStatusCode(404);
            
            return ;   
        }

        $out = $this->getBlockRendererService()
                        ->setBlock($block)
                        ->render();

        $response->setStatusCode(200);
        $response->setContent(utf8_decode($out)); 

        $response->getHeaders()->addHeaderLine('Access-Control-Allow-Origin', '*');
        
        return $response;
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
    * getCachedBlocks : Getter pour CachedBlock
    *
    * @return PlaygroundCMS\Cache\Blocks $blockService
    */
    private function getCachedBlocks()
    {
        if (null === $this->blockService) {
            $this->setCachedBlocks($this->getServiceLocator()->get('playgroundcms_cached_blocks'));
        }

        return $this->blockService;
    }

    /**
    * setCachedBlocks : Setter pour CachedBlock
    * @param PlaygroundCMS\Cache\Blocks $blockService
    *
    * @return ExportBlockController $this 
    */
    private function setCachedBlocks(Blocks $blockService)
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
            $this->setBlockRendererService($this->getServiceLocator()->get('playgroundcms_block_renderer'));
        }

        return $this->blockRenderer;
    }

    /**
    * setBlockRendererService : Setter pour blockRenderer
    * @param PlaygroundCMS\Renderer BlockRenderer $blockRenderer
    *
    * @return ExportBlockController $this  
    */
    private function setBlockRendererService(BlockRenderer $blockRenderer)
    {
        $this->blockRenderer = $blockRenderer;

        return $this;
    }
}
