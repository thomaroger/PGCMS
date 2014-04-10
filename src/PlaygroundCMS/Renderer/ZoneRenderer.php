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
            foreach ($blocks as $block) {
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
         /**
        * @todo : mettre en cache ces requetes
        */
        $blocklayoutZones = $this->getServiceManager()->get('playgroundcms_Blocklayoutzone_mapper')->findBy(array('layoutZone' => $layoutZone));
        
        foreach ($blocklayoutZones as $blocklayoutZone) {
            $blocks[] = $this->getServiceManager()->get('playgroundcms_cached_blocks')->findBlockById($blocklayoutZone->getBlock()->getId());
        }
        
        return $blocks;
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