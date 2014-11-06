<?php

/**
* @package : PlaygroundCMS\Blocks
* @author : troger
* @since : 20/10/2014
*
* Classe qui permet de gérer l'affichage d'un bloc de menu
**/

namespace PlaygroundCMS\Blocks;

use Zend\View\Model\ViewModel;
use PlaygroundCMS\Entity\Menu;


class MenuController extends AbstractBlockController
{
    protected $menuService;
    /**
    * {@inheritdoc}
    * renderBlock : Rendu du bloc d'un bloc HTML
    */
    protected function renderBlock()
    {
        $params = array();
        $block = $this->getBlock();

        $menuId = $block->getParam('menu');

        $menu = $this->getMenuService()->getMenuMapper()->findById($menuId);

        $repository = $this->getMenuService()->getMenuMapper()->getEntityRepository();
        
        $controller = $this;
        $options = array(
            'decorate' => true,
            'rootOpen' => '<ul class="nav navbar-nav">',
            'rootClose' => '</ul>',
            'childOpen' => '',
            'childClose' => '',
            'nodeDecorator' => function($node) use (&$controller) {
                return $controller->decorateNode($node);
            }
        );

        $params['htmlTree'] = $repository->childrenHierarchy($menu, false, $options);

        $model = new ViewModel($params);
        
        return $this->render($model);
    }

    public function decorateNode($node)
    {
        $menu = $this->getMenuService()->getMenuMapper()->findById($node['id']);
        $menu->setTranslatableLocale($this->getRessource()->getLocale());
        $this->getMenuService()->getMenuMapper()->getEntityManager()->refresh($menu); 

        $html = "";
        
        if($menu->getChildren()->count() > 0 && $menu->getStatus() == Menu::MENU_PUBLISHED) { 
            $html .= '<li class="dropdown">';
            $html .= '<a href="#" class="dropdown-toggle" data-toggle="dropdown">'.$menu->getTitle().' <span class="caret"></span></a>';
            $html .= '<ul class="dropdown-menu" role="menu">';
            foreach ($menu->getChildren() as $submenu) {
                if($submenu->getStatus() == Menu::MENU_PUBLISHED) {
                    $html .= '<li class=""><a href="'.$submenu->getUrl().'">'.$submenu->getTitle().'</a></li>';
                }
            }
            $html .= '</ul>';
            $html .= '</li>';
        }else {
            if($menu->getLevel() <= 2) {
                if($menu->getStatus() == Menu::MENU_PUBLISHED) {
                    $html .= '<li class=""><a href="'.$menu->getUrl().'">'.$menu->getTitle().'</a></li>';
                }
            }
        }

        return $html; 
    }

   
    /*protected function setHeaders(Response $response)
    {
        $response->setMaxAge(300);
        $response->setSharedMaxAge(300);
        $response->setPublic();
    }*/

    /**
    * __toString : Permet de decrire le bloc
    *
    * @return string $return : Block HTML
    */
    public function __toString()
    {
        
        return 'Block Menu';
    }

    /**
     * getFeedService : Recuperation du service de feed
     *
     * @return Feed $feedService : feedService
     */
    private function getMenuService()
    {
        if (null === $this->menuService) {
            $this->setMenuService($this->getServiceManager()->get('playgroundcms_menu_service'));
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
}
