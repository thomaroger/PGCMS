<?php
/**
* @package : PlaygroundCMS
* @author : troger
* @since : 29/07/2014
*
* Classe de service pour l'entite Menu
**/
namespace PlaygroundCMS\Service;

use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\ServiceManager\ServiceManager;
use ZfcBase\EventManager\EventProvider;
use PlaygroundCMS\Entity\Menu as MenuEntity;
use PlaygroundCore\Filter\Slugify;


class Menu extends EventProvider implements ServiceManagerAwareInterface
{

    /**
     * @var PlaygroundCMS\Mapper\Menu menuMapper
     */
    protected $menuMapper;

    /**
     * @var Zend\ServiceManager\ServiceManager ServiceManager
     */
    protected $serviceManager;

    protected $localeMapper;
    protected $ressourceMapper;


    public function create($data)
    {
        $menu = new MenuEntity();
        $menu->setStatus(MenuEntity::MENU_NOT_PUBLISHED);
        if ($data['menu']['published'] == 1) {
            $menu->setStatus(MenuEntity::MENU_PUBLISHED);
        }

        $locales = $this->getLocaleMapper()->findBy(array('active_front' => 1));
        $repository = $this->getMenuMapper()->getEntityManager()->getRepository($menu->getTranslationRepository());
        $slugify = new Slugify;


        foreach ($locales as $locale) {
            if(!empty($data['menu'][$locale->getLocale()])) {

                $url = "";
                if(!empty($data['menu'][$locale->getLocale()]['ext_page'])) {
                    $url = $data['menu'][$locale->getLocale()]['ext_page'];
                }
                if(!empty($data['menu'][$locale->getLocale()]['int_page'])) {
                    $url = $data['menu'][$locale->getLocale()]['int_page'];
                    $ressource = $this->getRessourceMapper()->findById($url);
                    $url = $ressource->getUrl();
                }

                $slug = $slugify->filter($data['menu'][$locale->getLocale()]['name']);
                $repository->translate($menu, 'title', $locale->getLocale(), $data['menu'][$locale->getLocale()]['name'])
                           ->translate($menu, 'slug', $locale->getLocale(), $slug)
                           ->translate($menu, 'url', $locale->getLocale(), $url);               
            }
            
        }


        $this->getMenuMapper()->getEntityRepository()->persistAsFirstChild($menu);
        $menu = $this->getMenuMapper()->persist($menu);
    }

    public function edit($data)
    {
        $menu = $this->getMenuMapper()->findById($data['id']);

        $menu->setStatus(MenuEntity::MENU_NOT_PUBLISHED);
        if ($data['menu']['published'] == 1) {
            $menu->setStatus(MenuEntity::MENU_PUBLISHED);
        }

        $locales = $this->getLocaleMapper()->findBy(array('active_front' => 1));
        $repository = $this->getMenuMapper()->getEntityManager()->getRepository($menu->getTranslationRepository());
        $slugify = new Slugify;


        foreach ($locales as $locale) {
            if(!empty($data['menu'][$locale->getLocale()])) {

                $url = "";
                if(!empty($data['menu'][$locale->getLocale()]['ext_page'])) {
                    $url = $data['menu'][$locale->getLocale()]['ext_page'];
                }
                if(!empty($data['menu'][$locale->getLocale()]['int_page'])) {
                    $url = $data['menu'][$locale->getLocale()]['int_page'];
                    $ressource = $this->getRessourceMapper()->findById($url);
                    $url = $ressource->getUrl();
                }

                $slug = $slugify->filter($data['menu'][$locale->getLocale()]['name']);
                $repository->translate($menu, 'title', $locale->getLocale(), $data['menu'][$locale->getLocale()]['name'])
                           ->translate($menu, 'slug', $locale->getLocale(), $slug)
                           ->translate($menu, 'url', $locale->getLocale(), $url);               
            }
            
        }

        $menu = $this->getMenuMapper()->update($menu);
    }


    public function checkMenu($data)
    {
        
        $locales = $this->getLocaleMapper()->findBy(array('active_front' => 1));
        $name = false;
        foreach ($locales as $locale) {
            if(!empty($data['menu'][$locale->getLocale()])) {
                if(!empty($data['menu'][$locale->getLocale()]['name'])){
                    $name = true;
                }
            }
        }

        if(!$name){

            return array('status' => 1, 'message' => 'One of name is required', 'data' => $data);
        }

        $name = false;
        foreach ($locales as $locale) {
            if(!empty($data['menu'][$locale->getLocale()])) {
                if(!empty($data['menu'][$locale->getLocale()]['name'])){
                    $name = true;

                    if (empty($data['menu'][$locale->getLocale()]['int_page']) && empty($data['menu'][$locale->getLocale()]['ext_page'])) {
                        return array('status' => 1, 'message' => 'A target page is required for the menu', 'data' => $data);
                    }

                    if (!empty($data['menu'][$locale->getLocale()]['int_page']) && !empty($data['menu'][$locale->getLocale()]['ext_page'])) {
                        return array('status' => 1, 'message' => 'One target page is required for the menu', 'data' => $data);
                    }
                }
            }
        }

        if(!$name){
            
            return array('status' => 1, 'message' => 'One of name is required', 'data' => $data);
        }


        return array('status' => 0, 'message' => '', 'data' => $data);
    }


    public function getRessourceMapper()
    {
        if (null === $this->ressourceMapper) {
            $this->ressourceMapper = $this->getServiceManager()->get('playgroundcms_ressource_mapper');
        }

        return $this->ressourceMapper;
    }

    /**
     * getLocaleMapper : Getter pour localeMapper
     *
     * @return PlaygroundCore\Mapper\Locale $localeMapper
     */
    public function getLocaleMapper()
    {
        if (null === $this->localeMapper) {
            $this->localeMapper = $this->getServiceManager()->get('playgroundcore_locale_mapper');
        }

        return $this->localeMapper;
    }
  
    public function getMenuMapper()
    {
        if (null === $this->menuMapper) {
            $this->menuMapper = $this->getServiceManager()->get('playgroundcms_menu_mapper');
        }

        return $this->menuMapper;
    }

     
    private function setMenuMapper(MenuMapper $menuMapper)
    {
        $this->menuMapper = $menuMapper;

        return $this;
    }

    /**
     * getServiceManager : Getter pour serviceManager
     *
     * @return ServiceManager
     */
    public function getServiceManager()
    {
        return $this->serviceManager;
    }

     /**
     * setServiceManager : Setter pour le serviceManager
     * @param  ServiceManager $serviceManager
     *
     * @return Menu $this
     */
    public function setServiceManager(ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;

        return $this;
    }
}