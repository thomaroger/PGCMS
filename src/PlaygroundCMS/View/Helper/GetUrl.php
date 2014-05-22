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
use PlaygroundCMS\Options\ModuleOptions;

class GetUrl extends AbstractHelper
{

    /**
    * @var Block $blockService
    */
    protected $ressourceService;

    protected $serviceManager;

    protected $cmsOptions;

    /**
     * __invoke : permet de rendre un bloc
     * @param  string $slug slug
     *
     * @return string $return 
     */
    public function __invoke($entity)
    {
        if (empty($entity)) {
         
            return "#";
        }

        $filters = array();
        $filters['model'] = get_class($entity);
        $filters['recordId'] = $entity->getId();
        $filters['locale'] = $this->getLocale();
        $ressource = $this->getRessourceService()->getRessourceMapper()->findOneBy($filters);
        
        if (empty($ressource)) {
         
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

    public function getLocale()
    {
        return $this->getCmsOptions()->getRessource()->getLocale();
    }


    /**
     * getPluginTranslator : Getter pour translator
     *
     * @return Zend\Mvc\I18n\Translator $translator
     */
    private function getCmsOptions()
    {
        if ($this->cmsOptions === null){
            $this->setCmsOptions($this->getServiceManager()->get('playgroundcms_module_options'));
        }

        return $this->cmsOptions;
    }

     /**
     * setPluginTranslator : Setter pour le translator
     * @param  Zend\Mvc\I18n\Translator $translator
     *
     * @return CMSTranslate
     */
    public function setCmsOptions(ModuleOptions $cmsOptions)
    {
        $this->cmsOptions = $cmsOptions;

        return $this;
    }
}