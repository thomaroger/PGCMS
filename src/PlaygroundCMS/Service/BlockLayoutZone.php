<?php
/**
* @package : PlaygroundCMS
* @author : troger
* @since : 10/04/2014
*
* Classe de service pour l'entite BlockLayoutZone
**/
namespace PlaygroundCMS\Service;

use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\ServiceManager\ServiceManager;
use ZfcBase\EventManager\EventProvider;
use PlaygroundCMS\Mapper\BlockLayoutZone as BlockLayoutZoneMapper;

class BlockLayoutZone extends EventProvider implements ServiceManagerAwareInterface
{

    /**
     * @var PlaygroundCMS\Mapper\Block blockMapper
     */
    protected $blockLayoutZoneMapper;

    /**
     * @var Zend\ServiceManager\ServiceManager ServiceManager
     */
    protected $serviceManager;
    
    /**
     * getBlockMapper : Getter pour blockMapper
     *
     * @return PlaygroundCMS\Mapper\Block $blockMapper
     */
    public function getBlockLayoutZoneMapper()
    {
        if (null === $this->blockLayoutZoneMapper) {
            $this->blockLayoutZoneMapper = $this->getServiceManager()->get('playgroundcms_blocklayoutzone_mapper');
        }

        return $this->blockLayoutZoneMapper;
    }

     /**
     * setBlockMapper : Setter pour le blockMapper
     * @param  PlaygroundCMS\Mapper\Block $blockMapper
     *
     * @return Block
     */
    private function setBlockLayoutZoneMapper(BlockLayoutZoneMapper $blockLayoutZoneMapper)
    {
        $this->blockLayoutZoneMapper = $blockLayoutZoneMapper;

        return $this;
    }

    /**
     * getServiceManager : Getter pour serviceManager
     *
     * @return ServiceManager
     */
    public function getServiceManager()
    {
        return $this->serviceManager;
    }

     /**
     * setServiceManager : Setter pour le serviceManager
     * @param  ServiceManager $serviceManager
     *
     * @return Block
     */
    public function setServiceManager(ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;

        return $this;
    }
}