<?php
/**
* @package : PlaygroundCMS
* @author : troger
* @since : 24/03/2013
*
* Classe qui permet de gérer le controlleur de base d'une entity
**/
namespace PlaygroundCMS\Controller\Front;

use Zend\Mvc\Controller\AbstractActionController as AbstractActionControllerParent;

class AbstractActionController extends AbstractActionControllerParent
{
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
   */
   protected function getEntity()
   {

        return '';
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

        return $template;
    }  
}
