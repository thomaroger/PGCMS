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
    /**
    * @var ZoneRenderer $zone
    */
    protected $zoneRenderer;

    /**
    * @var Zones $zones
    */
    protected $zones;
    
    /**
     * __invoke : permet de rendre un bloc
     * @param  string $slug slug
     *
     * @return string $return 
     */
    public function __invoke($slug)
    {
        $render = "";
        $zone = $this->getZoneFromCache((string) $slug);
        
        if(!empty($zone)) {
            $render = $this->getZoneRendererService()
                        ->setZone($zone)
                        ->render();    
        }

        echo $render;
    }

    /**
    *  getZoneFromCache : Recuperation d'une zone depuis le cache en fonction d'un slug
    * @param string $slug : slug de la zone a rechercher
    *
    * @return Zone $zone 
    */
    private function getZoneFromCache($slug)
    {
        $zone = $this->getCachedZones()->findZoneBySlug($slug);

        return $zone;
    }

    /**
    * getZoneRendererService : Getter pour zoneRenderer
    *
    * @return  ZoneRenderer $zoneRenderer
    */
    private function getZoneRendererService()
    {
        if (null === $this->zoneRenderer) {
            $this->zoneRenderer = $this->getServiceManager()->get('playgroundcms_zone_renderer');
        }

        return $this->zoneRenderer;
    }

    /**
    * setZoneRendererService : Setter pour zoneRenderer
    * @param ZoneRenderer $zoneRenderer
    *
    * @return GetZone $this 
    */
    public function setZoneRendererService(ZoneRenderer $zoneRenderer)
    {
        $this->zoneRenderer = $zoneRenderer;

        return $this;
    }

   /**
    * setCachedZones : Setter pour zones
    * @param Zones $zones
    *
    * @return GetZone $this 
    */
    public function setCachedZones($zones)
    {
        $this->zones = $zones;

        return $this;
    }

    /**
    * getCachedZones : Getter pour zones
    *
    * @return Zones $zones
    */
    public function getCachedZones()
    {
        return $this->zones;
    }
}