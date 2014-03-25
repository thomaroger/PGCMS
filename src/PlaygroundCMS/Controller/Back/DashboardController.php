<?php
/**
* @package : PlaygroundCMS
* @author : troger
* @since : 25/03/2014
*
* Classe de controleur  de back du dashboard du CMS
**/

namespace PlaygroundCMS\Controller\Back;

use Zend\View\Model\ViewModel;
use Zend\Mvc\Controller\AbstractActionController;

class DashboardController extends AbstractActionController
{
    /**
    * indexAction : Action index du controller de dashboard
    *
    * @return ViewModel $viewModel 
    */
    public function indexAction()
    {
        $this->layout()->setVariable('nav', "dashboard");
        
        return new ViewModel();
    }
}
