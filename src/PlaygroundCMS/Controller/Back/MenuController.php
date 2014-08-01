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
        $return  = array();
        $data = array();
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

        $repository = $this->getMenuService()->getMenuMapper()->getEntityRepository();
        
        $controller = $this;
        $options = array(
            'decorate' => true,
            'rootOpen' => '<ol class="dd-list">',
            'rootClose' => '</ol>',
            'childOpen' => '',
            'childClose' => '',
            'nodeDecorator' => function($node) use (&$controller) {
                return $controller->decorateNode($node);
            }
        );

        $htmlTree = $repository->childrenHierarchy(null, true, $options);



        return new ViewModel(array("menus" => $menus,
                                   "return" => $return,
                                   "data" => $data,
                                   'htmlTree' => $htmlTree,
                                   "ressources" => $ressources,
                                   "locales" => $locales));
    }  


    public function decorateNode($node)
    {

        $html = "";
        $html .= '<li class="dd-item"  data-id="'.$node['id'].'">';
        $html .= '<div class="dd-handle">';
        if ($node['status'] == 1 ) {
            $html .= '<div class="feed-item pull-left">
                        <div class="icon">
                            <i class="fa fa-check color-green"></i>
                        </div>
                    </div>';
        } else {
            $html .= '<div class="feed-item pull-left">
                        <div class="icon">
                            <i class="fa fa-times color-red"></i>
                        </div>
                    </div>';
        }

        $html .= '&nbsp;&nbsp;&nbsp; '.$node['id'] .'&nbsp;&nbsp;&nbsp; - &nbsp;&nbsp;&nbsp;';
        $html .=  $node['title'].' ('.$node['url'].')';
        $html .= '</div>';

   
        $html .= '<div class="dd-actions pull-right">
                    <a href="#" class="btn btn-xs btn-success">
                        <i class="btn-icon-only fa fa-pencil"></i>                                       
                    </a>
                    <a href="#" class="btn btn-xs btn-danger">
                        <i class="btn-icon-only fa fa-times"></i>                                       
                    </a>
                </div>';

        $html .= '</li>';

        return $html; 
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
