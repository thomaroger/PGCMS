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

    /**
    * @var BlockRenderer $blockRenderer : Renderer de bloc
    */
    protected $blockRenderer;
    
    /**
    * @var Block $blockService : Service de bloc
    */
    protected $blockService;
    
    /**
    * @var BlocksLayoutsZones $cachedBlocksLayoutsZones : Cached BlocksLayoutsZones
    */
    protected $cachedBlocksLayoutsZones;
    
    /**
    * @var ModuleOptions $cmsOptions : Options de playgroundCms
    */
    protected $cmsOptions;
    
    /**
    * @var Layouts $cachedLayouts : Caches Layouts
    */
    protected $cachedLayouts;
    
    /**
    * @var LayoutsZones $cachedLayoutsZones : Cached LayoutsZones
    */
    protected $cachedLayoutsZones;
    
    /**
    * @var Blocks $cachedBlocks : Cached blocks
    */
    protected $cachedBlocks;

    /**
    * setZone : Setter pour zone
    * @param Zone $zone : zone à rendre
    *
    * @return ZoneRenderer
    */
    public function setZone(Zone $zone)
    {
        $this->zone = $zone;

        return $this;
    }

    /**
    * getZone : Getter pour zone
    *
    * @return Zone $zone
    */    
    private function getZone()
    {
        return $this->zone;
    }

    /**
    * render : Rend un zone
    * 
    * @return string $render
    */
    public function render()
    {
         $out = '';

        // For debug
        $out .= "\n<!-- Render Zone : ".$this->getZone()->getId()." - ".$this->getZone()->getName()."-->\n";
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
        $out .= "\n<!-- / Render Zone : ".$this->getZone()->getName()."-->\n";

        return $out;
    }

    /**
    * getBlocks : Recuperation des blocs en fonction d'une zone
    *
    * @return array $blocks
    */
    public function getBlocks()
    {

        return $this->getBlocksInZone();
    }

    /**
    * getBlocksInZone : Recuperation des blocs en fonction d'une zone
    *
    * @return array $blocks
    */
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
     * @return ZoneRenderer
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
    * @return ZoneRenderer 
    */
    public function setBlockRendererService(BlockRenderer $blockRenderer)
    {
        $this->blockRenderer = $blockRenderer;

        return $this;
    }

    /**
    * getLayoutsCached : Getter pour Layouts
    *
    * @return Layouts $cachedLayouts
    */
    private function getLayoutsCached()
    {
        if (null === $this->cachedLayouts) {
            $this->setLayoutsCached($this->getServiceManager()->get('playgroundcms_cached_layouts'));
        }

        return $this->cachedLayouts;
    }

    /**
    * setLayoutsCached : Setter pour cachedLayouts
    * @param Layouts $cachedLayouts
    *
    * @return ZoneRenderer 
    */
    public function setLayoutsCached(Layouts $cachedLayouts)
    {
        $this->cachedLayouts = $cachedLayouts;

        return $this;
    }

   /**
    * setLayoutsZonesCached : Setter pour cachedLayoutsZones
    * @param LayoutsZones $cachedLayoutsZones
    *
    * @return ZoneRenderer 
    */
    public function setLayoutsZonesCached(LayoutsZones $cachedLayoutsZones)
    {
        $this->cachedLayoutsZones = $cachedLayoutsZones;

        return $this;
    }

    /**
    * getLayoutsZonesCached : Getter pour cachedLayoutsZones
    *
    * @return LayoutsZones $cachedLayoutsZones
    */
    private function getLayoutsZonesCached()
    {
        if (null === $this->cachedLayoutsZones) {
            $this->setLayoutsZonesCached($this->getServiceManager()->get('playgroundcms_cached_layoutszones'));
        }

        return $this->cachedLayoutsZones;
    }

    /**
    * setBlocksLayoutsZonesCached : Setter pour cachedBlocksLayoutsZones
    * @param BlocksLayoutsZones $cachedBlocksLayoutsZones
    *
    * @return ZoneRenderer 
    */
    public function setBlocksLayoutsZonesCached(BlocksLayoutsZones $cachedBlocksLayoutsZones)
    {
        $this->cachedBlocksLayoutsZones = $cachedBlocksLayoutsZones;

        return $this;
    }

    /**
    * getBlocksLayoutsZonesCached : Getter pour cachedBlocksLayoutsZones
    *
    * @return BlocksLayoutsZones $cachedBlocksLayoutsZones
    */
    private function getBlocksLayoutsZonesCached()
    {
        if (null === $this->cachedBlocksLayoutsZones) {
            $this->setBlocksLayoutsZonesCached($this->getServiceManager()->get('playgroundcms_cached_blockslayoutszones'));
        }

        return $this->cachedBlocksLayoutsZones;
    }


    /**
    * getBlocksCached : Getter pour cachedBlocks
    *
    * @return Blocks $cachedBlocks
    */
    private function getBlocksCached()
    {
        if (null === $this->cachedBlocks) {
            $this->setBlocksCached($this->getServiceManager()->get('playgroundcms_cached_blocks'));
        }

        return $this->cachedBlocks;
    }

    /**
    * setBlocksCached : Setter pour cachedBlocks
    * @param Blocks $cachedBlocks
    *
    * @return ZoneRenderer 
    */
    public function setBlocksCached(Blocks $cachedBlocks)
    {
        $this->cachedBlocks = $cachedBlocks;

        return $this;
    }

   /**
    * getCmsOptions : Getter pour cmsOptions
    *
    * @return ModuleOptions $cmsOptions
    */
    private function getCmsOptions()
    {
        if (null === $this->cmsOptions) {
            $this->setCmsOptions($this->getServiceManager()->get('playgroundcms_module_options'));
        }

        return $this->cmsOptions;
    }

    /**
    * setCmsOptions : Setter pour cmsOptions
    * @param ModuleOptions $cmsOptions
    *
    * @return ZoneRenderer 
    */
    public function setCmsOptions(ModuleOptions $cmsOptions)
    {
        $this->cmsOptions = $cmsOptions;

        return $this;
    }

}