<?php
/**
* @package : PlaygroundCMS
* @author : troger
* @since : 18/03/2014
*
* Classe de service pour l'entite Ressource
**/
namespace PlaygroundCMS\Service;

use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\ServiceManager\ServiceManager;
use ZfcBase\EventManager\EventProvider;
use PlaygroundCMS\Mapper\Ressource as RessourceMapper;

class Ressource extends EventProvider implements ServiceManagerAwareInterface
{

    /**
     * @var PlaygroundCMS\Mapper\Ressource ressourceMapper
     */
    protected $ressourceMapper;

    /**
     * @var Zend\ServiceManager\ServiceManager ServiceManager
     */
    protected $serviceManager;
    
    /**
     * getRessourceMapper : Getter pour ressourceMapper
     *
     * @return PlaygroundCMS\Mapper\Ressource $ressourceMapper
     */
    public function getRessourceMapper()
    {
        if (null === $this->ressourceMapper) {
            $this->ressourceMapper = $this->getServiceManager()->get('playgroundcms_ressource_mapper');
        }

        return $this->ressourceMapper;
    }

     /**
     * setRessourceMapper : Setter pour le ressourceMapper
     * @param  PlaygroundCMS\Mapper\Ressource $ressourceMapper
     *
     * @return Ressource $this
     */
    private function setRessourceMapper(RessourceMapper $ressourceMapper)
    {
        $this->ressourceMapper = $ressourceMapper;

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
     * @return Ressource $this
     */
    public function setServiceManager(ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;

        return $this;
    }
}