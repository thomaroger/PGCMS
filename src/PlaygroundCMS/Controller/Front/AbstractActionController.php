<?php
/**
* @package : PlaygroundCMS
* @author : troger
* @since : 24/03/2014
*
* Classe qui permet de gérer le controlleur de base d'une entity
**/
namespace PlaygroundCMS\Controller\Front;

use Zend\Mvc\Controller\AbstractActionController as AbstractActionControllerParent;
use PlaygroundCMS\Renderer\BlockRenderer;


class AbstractActionController extends AbstractActionControllerParent
{
    /**
    * @var Ressource $ressourceService
    */
    protected $ressourceService;

    /**
    * @var Block $blockService
    */
    protected $blockService;
    protected $layoutService;

    /**
    * @var ModuleOptions $cmsOptions
    */
    protected $cmsOptions;

    protected $blockRenderer;
    
    /**
    * getRessource : permet de récuperer une ressource
    *
    * @return Ressource $ressource
    */
    protected function getRessource()
    {
        $ressource = $this->params()->fromRoute('ressource', null);
        $this->getCmsOptions()->setRessource($ressource);

        return $ressource;
    }

    /**
    * getMatches : Permet de recuperer le matches du router
    *
    * @return array $getMatches
    */
    protected function getMatches()
    {
        return $this->params()->fromRoute('matches', array());
    }


    /**
    * Retourne l'entity en fonction de la langue
    *
    * @return *\Entity\* $entity
    */
    protected function getEntity()
    {
        $ressource = $this->getRessource();
        $entity = $this->getRessourceService()->getRessourceMapper()->getEntityRepositoryForEntity($ressource->getModel())->findOneById($ressource->getRecordId());

        if (empty($entity)) {
            $this->getResponse()->setStatusCode(404); 
        }  

        if (!method_exists($entity, "getTranslationRepository")) {
            throw new \RuntimeException(sprintf(
                'getTranslationRepository have to be defined in entity class, %s::%s() is missing.',
                get_class($entity),
                "getTranslationRepository"
            ));   
        }

        $translations = $this->getRessourceService()->getRessourceMapper()->getEntityRepositoryForEntity($entity->getTranslationRepository())->findTranslations($entity);
        $entity->setTranslations($translations[$ressource->getLocale()]);

        $this->getCmsOptions()->setEntity($entity);

        return $entity;
    }

    /**
    * getTemplate : Retourne le layout défini pour l'entité
    *
    * @return string $template
    */
    protected function getTemplate()
    {
        $ressource = $this->getRessource();
        $templates = json_decode($ressource->getLayoutContext(), true);
        $template = $templates['web'];

        if (is_numeric($template)) {
            $template = $this->getLayoutService()->getLayoutMapper()->findById($template);
            $template = $template->getFile();
        }

        $this->getServiceLocator()->get('playgroundcms_module_options')->setCurrentLayout($template);
        
        return $template;
    }  

    /**
     * getRessourceService : Getter pour le service de ressource
     *
     * @return Ressource $ressourceService
     */
    protected function getRessourceService()
    {
        if (!$this->ressourceService) {
            $this->ressourceService = $this->getServiceLocator()->get('playgroundcms_ressource_service');
        }

        return $this->ressourceService;
    }

    /**
     * getBlockService : Getter pour le service de block
     *
     * @return Block $blockService
     */
    protected function getBlockService()
    {
        if (!$this->blockService) {
            $this->blockService = $this->getServiceLocator()->get('playgroundcms_block_service');
        }

        return $this->blockService;
    }

     /**
     * getBlockService : Getter pour le service de block
     *
     * @return Block $blockService
     */
    protected function getLayoutService()
    {
        if (!$this->layoutService) {
            $this->layoutService = $this->getServiceLocator()->get('playgroundcms_layout_service');
        }

        return $this->layoutService;
    }

     /**
     * getCMSOptions : Getter pour les options de playgroundcms
     *
     * @return ModuleOptions $cmsOptions
     */
    protected function getCMSOptions()
    {
        if (!$this->cmsOptions) {
            $this->cmsOptions = $this->getServiceLocator()->get('playgroundcms_module_options');
        }

        return $this->cmsOptions;
    }

    /**
    * getBlockRendererService : Getter pour blockRenderer
    *
    * @return PlaygroundCMS\Renderer BlockRenderer $blockRenderer
    */
    protected function getBlockRendererService()
    {
        if (null === $this->blockRenderer) {
            $this->setBlockRendererService($this->getServiceLocator()->get('playgroundcms_block_renderer'));
        }

        return $this->blockRenderer;
    }

    /**
    * setBlockRendererService : Setter pour blockRenderer
    * @param PlaygroundCMS\Renderer BlockRenderer $blockRenderer
    *
    * @return ExportBlockController $this  
    */
    protected function setBlockRendererService(BlockRenderer $blockRenderer)
    {
        $this->blockRenderer = $blockRenderer;

        return $this;
    }
}
