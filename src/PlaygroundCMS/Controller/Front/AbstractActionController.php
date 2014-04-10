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

class AbstractActionController extends AbstractActionControllerParent
{
    /**
    * @var Ressource $ressourceService
    */
    protected $ressourceService;
   /**
   * getRessource : permet de récuperer une ressource
   *
   * @return Ressource $ressource
   */
   protected function getRessource()
   {

        return $this->params()->fromRoute('ressource', null);
   }

   /**
   * Retourne l'entity en fonction de la langue
   *
   * @return PlaygroundCMS\Entity\* $entity
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

        return $entity;
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
   * getTemplate : Retourne le layout défini pour l'entité
   *
   * @return string $template
   */
   protected function getTemplate()
    {
        $ressource = $this->getRessource();
        $templates = json_decode($ressource->getLayoutContext(), true);
        $template = $templates['web'];

        $this->getServiceLocator()->get('playgroundcms_module_options')->setCurrentLayout($template);
        return $template;
    }  

    protected function getRessourceService()
    {
        if (!$this->ressourceService) {
            $this->ressourceService = $this->getServiceLocator()->get('playgroundcms_ressource_service');
        }

        return $this->ressourceService;
    }
}
