<?php
/**
* @package : PlaygroundCMS
* @author : troger
* @since : 04/04/2014
*
* Classe de controleur de back pour la gestion des zones
**/

namespace PlaygroundCMS\Controller\Back;

use Zend\View\Model\ViewModel;
use Zend\Mvc\Controller\AbstractActionController;

class ZoneController extends AbstractActionController
{   
    /**
    * @var MAX_PER_PAGE  Nombre d'item par page
    */
    const MAX_PER_PAGE = 20;
    
    /**
    * @var Zone $zoneService Service de zone
    */
    protected $zoneService;
    
    /**
    * @var LayoutZone $layoutZoneService  Service de layoutZone
    */
    protected $layoutZoneService;

    /**
    * indexAction : Liste des zones
    *
    * @return ViewModel $viewModel 
    */
    public function listAction()
    {
        $layouts = array();
        $this->layout()->setVariable('nav', "cms");
        $this->layout()->setVariable('subNav', "zone");
        $p = $this->getRequest()->getQuery('page', 1);

        $zones = $this->getZoneService()->getZoneMapper()->findAll();
        
        $nbZones = count($zones);

        $zonesPaginator = new \Zend\Paginator\Paginator(new \Zend\Paginator\Adapter\ArrayAdapter($zones));
        $zonesPaginator->setItemCountPerPage(self::MAX_PER_PAGE);
        $zonesPaginator->setCurrentPageNumber($p);

        foreach ($zones as $zone) {
            $layoutsZone = $this->getLayoutZoneService()->getLayoutZoneMapper()->findBy(array('zone' => $zone));
            foreach ($layoutsZone as $layoutZone) {
                $layouts[$zone->getId()][] = $layoutZone->getLayout(); 
            }
        }

        return new ViewModel(array('zones'          => $zones,
                                   'zonesPaginator' => $zonesPaginator,
                                   'layouts'        => $layouts,
                                   'nbZones'        => $nbZones));
    }

 
    /**
    * getZoneService : Recuperation du service de zone
    *
    * @return Zone $zoneService : zoneService 
    */ 
    private function getZoneService()
    {
        if (!$this->zoneService) {
            $this->zoneService = $this->getServiceLocator()->get('playgroundcms_zone_service');
        }

        return $this->zoneService;
    }

    /**
    * getLayoutZoneService : Recuperation du service de layoutzone
    *
    * @return LayoutZone $layoutZoneService : layoutZoneService 
    */ 
    private function getLayoutZoneService()
    {
        if (!$this->layoutZoneService) {
            $this->layoutZoneService = $this->getServiceLocator()->get('playgroundcms_layoutzone_service');
        }

        return $this->layoutZoneService;
    }
}