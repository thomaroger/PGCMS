<?php
/**
* @package : PlaygroundCMS
* @author : troger
* @since : 10/04/2014
*
* Helper pour render une zone
**/
namespace PlaygroundCMS\View\Helper;

use Zend\View\Helper\AbstractHelper;
use PlaygroundCMS\Renderer\ZoneRenderer;

class getZone extends AbstractHelper
{

    protected $zoneRenderer;
    protected $zones;
    /**
     * __invoke : permet de rendre un bloc
     * @param  string $slug slug
     *
     * @return string $return 
     */
    public function __invoke($slug)
    {
        $zone = $this->getZoneFromCache((string) $slug);
        
        if(empty($zone)) {
            echo "";
        }
        
        echo $this->getZoneRendererService()
                        ->setZone($zone)
                        ->render();
    }

    private function getZoneFromCache($slug)
    {
        $zone = $this->getCachedZones()->findZoneBySlug($slug);

        return $zone;
    }

    /**
    * getBlockRendererService : Getter pour blockRenderer
    *
    * @return PlaygroundCMS\Renderer BlockRenderer $blockRenderer
    */
    private function getZoneRendererService()
    {
        if (null === $this->zoneRenderer) {
            $this->zoneRenderer = $this->getServiceManager()->get('playgroundcms_zone_renderer');
        }

        return $this->zoneRenderer;
    }

    /**
    * setBlockRendererService : Setter pour blockRenderer
    * @param PlaygroundCMS\Renderer BlockRenderer $blockRenderer
    *
    * @return GetBlock 
    */
    public function setZoneRendererService(ZoneRenderer $zoneRenderer)
    {
        $this->zoneRenderer = $zoneRenderer;

        return $this;
    }

    public function setCachedZones($zones)
    {
        $this->zones = $zones;

        return $this;
    }

     public function getCachedZones()
    {
        return $this->zones;
    }
}