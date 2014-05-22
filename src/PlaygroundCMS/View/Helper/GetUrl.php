<?php
/**
* @package : PlaygroundCMS
* @author : troger
* @since : 21/05/2014
*
* Helper pour recuperer l'url d'une entity
**/
namespace PlaygroundCMS\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\ServiceManager\ServiceManager;
use PlaygroundCMS\Service\Ressource;

class GetUrl extends AbstractHelper
{

    /**
    * @var Block $blockService
    */
    protected $ressourceService;

    protected $serviceManager;

    /**
     * __invoke : permet de rendre un bloc
     * @param  string $slug slug
     *
     * @return string $return 
     */
    public function __invoke($entity, $locale)
    {
        if(empty($entity) || empty($locale)){
         
            return "#";
        }

        $filters = array();
        $filters['model'] = get_class($entity);
        $filters['recordId'] = $entity->getId();
        $filters['locale'] = $locale;
        $ressource = $this->getRessourceService()->getRessourceMapper()->findOneBy($filters);
        
        if(empty($ressource)){
         
            return "#";
        }

        return $ressource->getUrl();
    }


    /**
     * setServiceManager : Setter pour le serviceManager
     * @param  ServiceManager $serviceManager
     *
     * @return CMSTranslate
     */
    public function setServiceManager(ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;

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
    * getBlockRendererService : Getter pour blockRenderer
    *
    * @return PlaygroundCMS\Renderer BlockRenderer $blockRenderer
    */
    private function getRessourceService()
    {
        if ($this->ressourceService === null){
            $this->setRessourceService($this->getServiceManager()->get('playgroundcms_ressource_service'));
        }

        return $this->ressourceService;
    }

    /**
    * setBlockRendererService : Setter pour blockRenderer
    * @param PlaygroundCMS\Renderer BlockRenderer $blockRenderer
    *
    * @return GetBlock  $this
    */
    public function setRessourceService(Ressource $ressourceService)
    {
        $this->ressourceService = $ressourceService;

        return $this;
    }
}