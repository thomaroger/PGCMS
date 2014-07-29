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