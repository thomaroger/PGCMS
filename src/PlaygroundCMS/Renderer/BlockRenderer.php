<?php
/**
* @package : PlaygroundCMS
* @author : troger
* @since : 18/03/2014
*
* Classe qui permet de rendre un bloc
**/
namespace PlaygroundCMS\Renderer;

use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\ServiceManager\ServiceManager;
use ZfcBase\EventManager\EventProvider;
use PlaygroundCMS\Entity\Block;

class BlockRenderer extends EventProvider implements ServiceManagerAwareInterface
{
    /**
    * @var Block $block : Bloc à rendre
    */
    protected $block;

    /**
    * setBlock : Setter pour bloc
    * @param Block $block : bloc à rendre
    *
    * @return BlockRenderer
    */
    public function setBlock(Block $block)
    {
        $this->block = $block;

        return $this;
    }

    /**
    * getBlock : Getter pour bloc
    *
    * @return Block $block
    */    
    private function getBlock()
    {

        return $this->block;
    }

    /**
    * render : Rend un bloc
    * 
    * @return string $render
    */
    public function render($format="html")
    {

        return $this->getRenderAction($format);
    }

    /**
    * getRenderAction : Genere le bloc
    * 
    * @return string $render
    */
    private function getRenderAction($format)
    {
        $block = $this->getBlock();
        $blockResponse = $this->getServiceManager()->get('playgroundcms_block_generator')->generate($block, $format);
        return sprintf('
    <!-- Render Block -> %s : %d - %s - %s-->
        %s
    <!-- / Render Block -> %s : %s -->
',
            $block->getType(),
            $block->getId(),
            $block->getName(),
            $block->getTemplateContext(),
            $blockResponse,
            $block->getType(),
            $block->getName()
        );
    }
   
    /**
     * getServiceManager : Getter pour le serviceManager
     *
     * @return ServiceManager
     */
    private function getServiceManager()
    {
        
        return $this->serviceManager;
    }

    /**
     * setServiceManager : Setter pour le serviceManagee
     * @param  ServiceManager $serviceManager
     *
     * @return BlockRenderer
     */
    public function setServiceManager(ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;

        return $this;
    }
}