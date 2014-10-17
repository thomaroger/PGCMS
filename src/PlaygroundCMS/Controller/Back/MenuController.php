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

        $root = $this->getMenuService()->findOrCreateRoot();
        $htmlTree = $repository->childrenHierarchy($root, false, $options);

        return new ViewModel(array("menus" => $menus,
                                   "return" => $return,
                                   "data" => $data,
                                   'htmlTree' => $htmlTree,
                                   "ressources" => $ressources,
                                   "locales" => $locales));
    }  


    public function editAction()
    {
        $this->layout()->setVariable('nav', "cms");
        $this->layout()->setVariable('subNav', "menu");
        $return  = array();
        $data = array();
        $ressources = array();
        

        $locales = $this->getLocaleService()->getLocaleMapper()->findBy(array('active_front' => 1));
        foreach ($locales as $locale) {
            $ressources[$locale->getLocale()] = $this->getRessourceService()->getRessourceMapper()->findBy(array('locale' => $locale->getLocale()));
        }
        
        $request = $this->getRequest();

        $menuId = $this->getEvent()->getRouteMatch()->getParam('id');
        $menu = $this->getMenuService()->getMenuMapper()->findById($menuId);

        $translations = $this->getMenuService()->getMenuMapper()->getEntityRepositoryForEntity($menu->getTranslationRepository())->findTranslations($menu);
        $menu->setTranslations($translations);

        if(empty($menu)){

            return $this->redirect()->toRoute('admin/playgroundcmsadmin/menus');
        }

        if ($request->isPost()) {
            $data = array_merge(
                    $request->getPost()->toArray(),
                    $request->getFiles()->toArray()
            );

            $return = $this->getMenuService()->checkMenu($data);
            $data = $return["data"];
            unset($return["data"]);

            if ($return['status'] == 0) {
                $this->getMenuService()->edit($data);

                return $this->redirect()->toRoute('admin/playgroundcmsadmin/menu');
            }
        }



        return new ViewModel(array('menu' => $menu,
                                   "ressources" => $ressources,
                                   "locales" => $locales));
    }

    public function removeAction()
    {
        $menuId = $this->getEvent()->getRouteMatch()->getParam('id');
        $menu = $this->getMenuService()->getMenuMapper()->findById($menuId);

        if(empty($menu)){

            return $this->redirect()->toRoute('admin/playgroundcmsadmin/menu');
        }

        $this->getMenuService()->getMenuMapper()->remove($menu);

        return $this->redirect()->toRoute('admin/playgroundcmsadmin/menu');
    }

    public function positionAction()
    {
        $request = $this->getRequest();
        $response = $this->getResponse();
        $response->setStatusCode(200);

        $return['status'] = 1;


        if ($request->isPost()) {
            $data = array_merge(
                    $request->getPost()->toArray(),
                    $request->getFiles()->toArray()
            );
            $repository = $this->getMenuService()->getMenuMapper()->getEntityRepository();
            $menus = json_decode($data['data'], true);
            foreach ($menus as $key => $menu) {
                if(is_numeric($menu)){
                    $menu = $this->getMenuService()->getMenuMapper()->findById($menu);
                    $root = $this->getMenuService()->findOrCreateRoot();
                    if($menu == $root) {
                        continue;

                    }
                    $this->getMenuService()->getMenuMapper()->getEntityRepository()->persistAsFirstChild($root)->persistAsLastChildOf($menu, $root);
                    $this->getMenuService()->getMenuMapper()->getEntityManager()->flush();
                    $menuParent = $menu;
                } else {
                    $menu = str_replace('children-', '', $menu);
                    $submenu = $this->getMenuService()->getMenuMapper()->findById($menu);
                    $submenu->setParent($menuParent);
                    $submenu = $this->getMenuService()->getMenuMapper()->persist($submenu);

                }
            }
            $return['status'] = 0;
        }
        
        
        $response->setContent(json_encode($return));
        
        return $response;
    }  


    public function decorateNode($node)
    {
        $menu = $this->getMenuService()->getMenuMapper()->findById($node['id']);
        $isRoot = "";
        
        if ($menu->getTitle() == "root") {
            $isRoot ="isRoot";
        }

        $html = "";
        $html .= '<li class="dd-item '.$isRoot.'"  data-id="'.$node['id'].'">';
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
        $html .=  $menu->getTitle().' ('.$menu->getUrl().')';
        $html .= '</div>';

        if($menu->getLevel() > 0) {
            $html .= '<div class="dd-actions pull-right">
                        <a href="/admin/playgroundcms/menu/edit/'.$node['id'].'" class="btn btn-xs btn-success">
                            <i class="btn-icon-only fa fa-pencil"></i>                                       
                        </a>
                        <a href="/admin/playgroundcms/menu/delete/'.$node['id'].'" class="btn btn-xs btn-danger">
                            <i class="btn-icon-only fa fa-times"></i>                                       
                        </a>
                    </div>';
        }

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
