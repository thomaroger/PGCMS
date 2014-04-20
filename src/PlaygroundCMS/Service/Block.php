<?php
/**
* @package : PlaygroundCMS
* @author : troger
* @since : 18/03/2014
*
* Classe de service pour l'entite Block
**/
namespace PlaygroundCMS\Service;

use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\ServiceManager\ServiceManager;
use ZfcBase\EventManager\EventProvider;
use PlaygroundCMS\Mapper\Block as BlockMapper;

class Block extends EventProvider implements ServiceManagerAwareInterface
{

    /**
     * @var PlaygroundCMS\Mapper\Block blockMapper
     */
    protected $blockMapper;

    /**
     * @var Zend\ServiceManager\ServiceManager ServiceManager
     */
    protected $serviceManager;
    
    public function getBlocksType()
    {
        $blockstype = array();

        $config = $this->getServiceManager()->get('config');
        $paths = $config['blocksType'];

        foreach ($paths as $path) {
            $path = __DIR__.'/../Blocks/';
            $dir = opendir($path);

            while($item = readdir($dir)) {
                if (is_file($sub = $path.'/'.$item)) {
                    // garder uniquement les blocs et non les abstracts
                    if(pathinfo($path.'/'.$item, PATHINFO_EXTENSION) == "php" && strpos($item, 'Abstract') === false) {
                        $blockstype[] = str_replace('Service', 'Blocks', __NAMESPACE__).'\\'.str_replace('.php','',$item);
                    }
                }
            }
        }

        return $blockstype;
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
     * setBlockMapper : Setter pour le blockMapper
     * @param  PlaygroundCMS\Mapper\Block $blockMapper
     *
     * @return Block
     */
    private function setBlockMapper(BlockMapper $blockMapper)
    {
        $this->blockMapper = $blockMapper;

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