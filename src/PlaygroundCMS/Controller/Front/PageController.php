<?php
/**
* @package : PlaygroundCMS
* @author : troger
* @since : 18/03/2014
*
* Classe de controleur de page
**/
namespace PlaygroundCMS\Controller\Front;

use Zend\View\Model\ViewModel;
use PlaygroundCMS\Controller\Front\AbstractActionController;

class PageController extends AbstractActionController
{
    /**
    * indexAction : Index du Controller de page
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

        $viewModel = new ViewModel(array('entity' => $entity,
                                        'ressource' => $ressource));
        
        return $viewModel->setTemplate($this->getTemplate());
    }
}
