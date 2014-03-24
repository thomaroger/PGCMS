<?php
/**
* @package : PlaygroundCMS
* @author : troger
* @since : 18/03/2013
*
* Classe de controleur de page
**/
namespace PlaygroundCMS\Controller\Front;

use Zend\View\Model\ViewModel;
use PlaygroundCMS\Controller\Front\AbstractActionController;

class PageController extends AbstractActionController
{
    /**
    * indexAction : Action index du controller de page
    *
    * @return ViewModel $viewModel 
    */
    public function indexAction()
    {
        $ressource = $this->getRessource();
        $entity = $this->getEntity();

        $result = $entity->checkVisibility();

        if($result === false){
            $this->getResponse()->setStatusCode(404);

            return;
        }

        if(!$entity->getIsWeb()) {
            $this->getResponse()->setStatusCode(404);

            return;
        }

        $viewModel = new ViewModel(array('entity' => $entity));
        
        return $viewModel->setTemplate($this->getTemplate());
    }
}
