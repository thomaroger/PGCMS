<?php
/**
* @package : PlaygroundCMS
* @author : troger
* @since : 29/07/2014
*
* Classe de controleur de back des menus
**/

namespace PlaygroundCMS\Controller\Back;

use Zend\View\Model\ViewModel;
use Zend\Mvc\Controller\AbstractActionController;

class MenuController extends AbstractActionController
{

    /**
    * @var $feedService : Service de feed
    */
    protected $menuService;

    protected $localeService;

    protected $ressourceService;
    
    /**
    * indexAction : Liste des feeds
    *
    * @return ViewModel $viewModel 
    */
    public function listAction()
    {
        $this->layout()->setVariable('nav', "cms");
        $this->layout()->setVariable('subNav', "menu");
        $ressources = array();
        $request = $this->getRequest();


        $locales = $this->getLocaleService()->getLocaleMapper()->findBy(array('active_front' => 1));
        foreach ($locales as $locale) {
            $ressources[$locale->getLocale()] = $this->getRessourceService()->getRessourceMapper()->findBy(array('locale' => $locale->getLocale()));
        }

        $menus = $this->getMenuService()->getMenuMapper()->findBy(array());   

        if ($request->isPost()) {
            $data = array_merge(
                    $request->getPost()->toArray(),
                    $request->getFiles()->toArray()
            );

            $return = $this->getMenuService()->checkMenu($data);
            $data = $return["data"];
            unset($return["data"]);

            if ($return['status'] == 0) {
                $this->getMenuService()->create($data);

                return $this->redirect()->toRoute('admin/playgroundcmsadmin/menu');
            }
        }

        return new ViewModel(array("menus" => $menus,
                                   "ressources" => $ressources,
                                   "locales" => $locales));
    }  

     /**
     * getFeedService : Recuperation du service de feed
     *
     * @return Feed $feedService : feedService
     */
    private function getMenuService()
    {
        if (null === $this->menuService) {
            $this->setMenuService($this->getServiceLocator()->get('playgroundcms_menu_service'));
        }

        return $this->menuService;
    }
    /**
     * setFeedService : Setter du service de feed
     * @param  Feed $feedService
     *
     * @return FeedController $this
     */
    private function setMenuService($menuService)
    {
        $this->menuService = $menuService;

        return $this;
    }


    /**
    * getLocaleService : Recuperation du service de locale
    *
    * @return Locale $localeService 
    */
    private function getLocaleService()
    {
        if (!$this->localeService) {
            $this->localeService = $this->getServiceLocator()->get('playgroundcore_locale_service');
        }

        return $this->localeService;
    }

    /**
    * getRessourceService : Recuperation du service de Ressource
    *
    * @return Ressource $ressourceService 
    */
    private function getRessourceService()
    {
        if (!$this->ressourceService) {
            $this->ressourceService = $this->getServiceLocator()->get('playgroundcms_ressource_service');
        }

        return $this->ressourceService;
    }
}
