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
    const MAX_PER_PAGE = 20;
    protected $zoneService;
    protected $layoutZoneService;

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

 

    protected function getZoneService()
    {
        if (!$this->zoneService) {
            $this->zoneService = $this->getServiceLocator()->get('playgroundcms_zone_service');
        }

        return $this->zoneService;
    }

    protected function getLayoutZoneService()
    {
        if (!$this->layoutZoneService) {
            $this->layoutZoneService = $this->getServiceLocator()->get('playgroundcms_layoutzone_service');
        }

        return $this->layoutZoneService;
    }
}