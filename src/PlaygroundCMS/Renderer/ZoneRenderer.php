<?php
/**
* @package : PlaygroundCMS
* @author : troger
* @since : 10/04/2014
*
* Classe qui permet de rendre une zone
**/
namespace PlaygroundCMS\Renderer;

use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\ServiceManager\ServiceManager;
use ZfcBase\EventManager\EventProvider;
use PlaygroundCMS\Entity\Zone;
use PlaygroundCMS\Entity\Block;
use PlaygroundCMS\Cache\BlocksLayoutsZones;
use PlaygroundCMS\Cache\Layouts;
use PlaygroundCMS\Cache\LayoutsZones;
use PlaygroundCMS\Cache\Blocks;
use PlaygroundCMS\Options\ModuleOptions;

class ZoneRenderer extends EventProvider implements ServiceManagerAwareInterface
{
    /**
    * @var Zone $zone : Bloc à rendre
    */
    protected $zone;
    protected $blockRenderer;
    protected $blockService;
    protected $cachedBlocksLayoutsZones;
    protected $cmsOptions;
    protected $cachedLayouts;
    protected $cachedLayoutsZones;
    protected $cachedBlocks;

    /**
    * setBlock : Setter pour bloc
    * @param Block $block : bloc à rendre
    *
    * @return BlockRenderer
    */
    public function setZone(Zone $zone)
    {
        $this->zone = $zone;

        return $this;
    }

    /**
    * getBlock : Getter pour bloc
    *
    * @return Block $block
    */    
    private function getZone()
    {
        return $this->zone;
    }

    /**
    * render : Rend un bloc
    * 
    * @return string $render
    */
    public function render()
    {
         $out = '';

        // For debug
        $out .= "\n<!-- Render zone ".$this->getZone()->getName()."-->\n";
        $blocks = $this->getBlocks();

        if (!empty($blocks)) {
            foreach ($blocks as $slug) {
                $block = $this->getBlocksCached()->findBlockBySlug($slug);

                if($block instanceOf Block) {
                    $out .= $this->getBlockRendererService()->setBlock($block)->render();
                }
            }
        }
        // For debug
        $out .= "\n<!-- / Render zone ".$this->getZone()->getName()."-->\n";

        return $out;
    }

    public function getBlocks()
    {

        return $this->getBlocksInZone();
    }

    public function getBlocksInZone()
    {
        $blocks = array();
        $currentLayout = $this->getCmsOptions()->getCurrentLayout();
        $layoutId = $this->getLayoutsCached()->findLayoutByFile($currentLayout);
        $layoutZone = $this->getLayoutsZonesCached()->findLayoutZoneByLayoutAndZone($layoutId, $this->getZone()->getId());
     
        return $this->getBlocksLayoutsZonesCached()->findBlocksByLayoutZone($layoutZone);
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

     /**
    * getBlockRendererService : Getter pour blockRenderer
    *
    * @return PlaygroundCMS\Renderer BlockRenderer $blockRenderer
    */
    private function getBlockRendererService()
    {
        if (null === $this->blockRenderer) {
            $this->setBlockRendererService($this->getServiceManager()->get('playgroundcms_block_renderer'));
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

      /**
    * getBlockRendererService : Getter pour blockRenderer
    *
    * @return PlaygroundCMS\Renderer BlockRenderer $blockRenderer
    */
    private function getLayoutsCached()
    {
        if (null === $this->cachedLayouts) {
            $this->setLayoutsCached($this->getServiceManager()->get('playgroundcms_cached_layouts'));
        }

        return $this->cachedLayouts;
    }

    /**
    * setBlockRendererService : Setter pour blockRenderer
    * @param PlaygroundCMS\Renderer BlockRenderer $blockRenderer
    *
    * @return GetBlock 
    */
    public function setLayoutsCached(Layouts $cachedLayouts)
    {
        $this->cachedLayouts = $cachedLayouts;

        return $this;
    }

    /**
    * setBlockRendererService : Setter pour blockRenderer
    * @param PlaygroundCMS\Renderer BlockRenderer $blockRenderer
    *
    * @return GetBlock 
    */
    public function setLayoutsZonesCached(LayoutsZones $cachedLayoutsZones)
    {
        $this->cachedLayoutsZones = $cachedLayoutsZones;

        return $this;
    }

      /**
    * getBlockRendererService : Getter pour blockRenderer
    *
    * @return PlaygroundCMS\Renderer BlockRenderer $blockRenderer
    */
    private function getLayoutsZonesCached()
    {
        if (null === $this->cachedLayoutsZones) {
            $this->setLayoutsZonesCached($this->getServiceManager()->get('playgroundcms_cached_layoutszones'));
        }

        return $this->cachedLayoutsZones;
    }

     /**
    * setBlockRendererService : Setter pour blockRenderer
    * @param PlaygroundCMS\Renderer BlockRenderer $blockRenderer
    *
    * @return GetBlock 
    */
    public function setBlocksLayoutsZonesCached(BlocksLayoutsZones $cachedBlocksLayoutsZones)
    {
        $this->cachedBlocksLayoutsZones = $cachedBlocksLayoutsZones;

        return $this;
    }

      /**
    * getBlockRendererService : Getter pour blockRenderer
    *
    * @return PlaygroundCMS\Renderer BlockRenderer $blockRenderer
    */
    private function getBlocksLayoutsZonesCached()
    {
        if (null === $this->cachedBlocksLayoutsZones) {
            $this->setBlocksLayoutsZonesCached($this->getServiceManager()->get('playgroundcms_cached_blockslayoutszones'));
        }

        return $this->cachedBlocksLayoutsZones;
    }


     /**
    * getBlockRendererService : Getter pour blockRenderer
    *
    * @return PlaygroundCMS\Renderer BlockRenderer $blockRenderer
    */
    private function getBlocksCached()
    {
        if (null === $this->cachedBlocks) {
            $this->setBlocksCached($this->getServiceManager()->get('playgroundcms_cached_blocks'));
        }

        return $this->cachedBlocks;
    }

    /**
    * setBlockRendererService : Setter pour blockRenderer
    * @param PlaygroundCMS\Renderer BlockRenderer $blockRenderer
    *
    * @return GetBlock 
    */
    public function setBlocksCached(Blocks $cachedBlocks)
    {
        $this->cachedBlocks = $cachedBlocks;

        return $this;
    }

      /**
    * getBlockRendererService : Getter pour blockRenderer
    *
    * @return PlaygroundCMS\Renderer BlockRenderer $blockRenderer
    */
    private function getCmsOptions()
    {
        if (null === $this->cmsOptions) {
            $this->setCmsOptions($this->getServiceManager()->get('playgroundcms_module_options'));
        }

        return $this->cmsOptions;
    }

    /**
    * setBlockRendererService : Setter pour blockRenderer
    * @param PlaygroundCMS\Renderer BlockRenderer $blockRenderer
    *
    * @return GetBlock 
    */
    public function setCmsOptions(ModuleOptions $cmsOptions)
    {
        $this->cmsOptions = $cmsOptions;

        return $this;
    }

}