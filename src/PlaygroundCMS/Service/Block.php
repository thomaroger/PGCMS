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
use PlaygroundCMS\Entity\Block as BlockEntity;
use PlaygroundCore\Filter\Slugify;

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

    /**
    * create : Permet de créer un bloc
    * @param array $data : tableau de données 
    * @param BlockForm $form : formulaire associé au bloc
    *
    * @return Block $block
    */
    public function create($data, $form){

        $block = new BlockEntity();

        $block->setName($data['name']);
        $block->setType($data['type']);
        $block->setIsExportable($data['is_exportable']);
        $block->setIsGallery($data['is_gallery']);
        $block->setIsEntityDetail($data['is_entity_detail']);

        $data = $form->decorateSpecificConfguration($data);

        $block->setConfiguration(json_encode($data['configuration']));
        $block->setTemplateContext(json_encode($data['template_context']));

        $block = $this->getBlockMapper()->insert($block);

        return $block;

    }

    /**
    * update : Permet de modifer un bloc
    * @param Block $block : bloc
    * @param array $data : tableau de données 
    * @param BlockForm $form : formulaire associé au bloc
    *
    * @return Block $block
    */
    public function update($block, $data, $form){

        $block->setName($data['name']);
        $block->setType($data['type']);
        $block->setIsExportable($data['is_exportable']);
        $block->setIsGallery($data['is_gallery']);
        $block->setIsEntityDetail($data['is_entity_detail']);

        $data = $form->decorateSpecificConfguration($data);

        $block->setConfiguration(json_encode($data['configuration']));
        $block->setTemplateContext(json_encode($data['template_context']));

        $block = $this->getBlockMapper()->insert($block);

        return $block;
        
    }

    /**
    * checkBlock : Permet de verifier si le form est valid
    * @param array $data : tableau de données 
    *
    * @return array $result
    */
    public function checkBlock($data)
    {
        
        // Il faut au moins une plateforme d'activer
        if (empty($data['template_context']['web']) && empty($data['template_context']['mobile'])) {

            return array('status' => 1, 'message' => 'One of platform must have a template', 'data' => $data);
        }

        // Il faut un nom
        if(empty($data['name'])) {

            return array('status' => 1, 'message' => 'name is required', 'data' => $data);  
        }

        // Il faut une visibility
        /*if(empty($data['configuration'])) {

            return array('status' => 1, 'message' => 'configuration is required', 'data' => $data);  
        } */      

        return array('status' => 0, 'message' => '', 'data' => $data);
    }
    
    /**
    * getBlocksType : Recuperation des types de blocks
    *
    * @return array $blockstype
    */
    public function getBlocksType()
    {
        $blockstype = array();

        $config = $this->getServiceManager()->get('config');
        $paths = $config['blocksType'];

        $slugify = new Slugify;

        foreach ($paths as $path) {
            $dir = opendir($path);

            while($item = readdir($dir)) {
                if (is_file($sub = $path.'/'.$item)) {
                    // garder uniquement les blocs et non les abstracts
                    if(pathinfo($path.'/'.$item, PATHINFO_EXTENSION) == "php" && strpos($item, 'Abstract') === false) {
                        $subSlash = explode('/', $sub);  
                        $blockName = $subSlash[count($subSlash)-3].'\\'.$subSlash[count($subSlash)-2].'\\'.str_replace('.php','',$item);                      
                        $blockstype[$slugify->filter($blockName)] = $blockName;
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
     * @return Block $this
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
     * @return Block $this
     */
    public function setServiceManager(ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;

        return $this;
    }
}