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
use PlaygroundCMS\Entity\BlockLayoutZone as BlockLayoutZoneEntity;

class BlockLayoutZone extends EventProvider implements ServiceManagerAwareInterface
{

    const DEFAULT_BLOCK_POSITION = 99;
    /**
     * @var PlaygroundCMS\Mapper\Block blockMapper
     */
    protected $blockLayoutZoneMapper;
    protected $layoutZoneMapper;
    protected $blockMapper;


    /**
     * @var Zend\ServiceManager\ServiceManager ServiceManager
     */
    protected $serviceManager;

    public function create($data)
    {
        $layoutZone = $this->getLayoutZoneMapper()->findOneBy(array('layout' => $data['layout']['id'], 'zone' => $data['layout']['zone']));
        $block = $this->getBlockMapper()->findById($data['layout']['block']);

        $blockLayoutZoneEntity = new BlockLayoutZoneEntity;
        $blockLayoutZoneEntity->setBlock($block);
        $blockLayoutZoneEntity->setLayoutZone($layoutZone);
        $blockLayoutZoneEntity->setPosition(self::DEFAULT_BLOCK_POSITION);

        $this->getBlockLayoutZoneMapper()->insert($blockLayoutZoneEntity);
    }

    public function checkData($data)
    {
        if(empty($data['layout']['id'])){
            
            return array('status' => 1, 'message' => 'Layout is required', 'data' => $data);
        }

        if(empty($data['layout']['zone'])){
            
            return array('status' => 1, 'message' => 'Zone is required', 'data' => $data);
        }

        if(empty($data['layout']['block'])){
            
            return array('status' => 1, 'message' => 'Block is required', 'data' => $data);
        }

        $layoutZone = $this->getLayoutZoneMapper()->findBy(array('layout' => $data['layout']['id'], 'zone' => $data['layout']['zone']));
        if(empty($layoutZone)){
            
            return array('status' => 1, 'message' => 'LayoutZone is required', 'data' => $data);
        }

        $block = $this->getBlockMapper()->findById($data['layout']['block']);
        if(empty($block)){

            return array('status' => 1, 'message' => 'block is required', 'data' => $data);
        }


        return array('status' => 0, 'message' => '', 'data' => $data);
    }


    /**
     * getBlockMapper : Getter pour blockMapper
     *
     * @return PlaygroundCMS\Mapper\Block $blockMapper
     */
    public function getBlockMapper()
    {
        if (null === $this->blockMapper) {
            $this->blockMapper = $this->getServiceManager()->get('playgroundcms_block_mapper');
        }

        return $this->blockMapper;
    }

    /**
     * getBlockMapper : Getter pour blockMapper
     *
     * @return PlaygroundCMS\Mapper\Block $blockMapper
     */
    public function getLayoutZoneMapper()
    {
        if (null === $this->layoutZoneMapper) {
            $this->layoutZoneMapper = $this->getServiceManager()->get('playgroundcms_layoutzone_mapper');
        }

        return $this->layoutZoneMapper;
    }

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