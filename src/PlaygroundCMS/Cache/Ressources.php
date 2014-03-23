<?php
/**
* @package : PlaygroundCMS
* @author : troger
* @since : 23/03/2013
*
* Classe qui permet de gérer le cache fichier de objets de type Ressource
**/
namespace PlaygroundCMS\Cache;

use PlaygroundCMS\Service\Block;

class Ressources extends CachedCollection
{
     /**
    * @var Ressource $ressourceService : Instance du service de ressource
    */
    protected $ressourceService;

    /**
    * getCachedRessources : Recuperation des ressources cachées
    *
    * @return array $ressources : Resources qui sont cachées
    */
    public function getCachedRessources()
    {
        $this->setType('ressources');
        
        return $this->getCachedCollection();
    }

    /**
    * findRessourceByUrl : Recuperation d'un ressource en fonction d'une url
    *
    * @return Ressource $ressource: ressource
    */
    public function findRessourceByUrl($url)
    {
        $ressources = $this->getCachedRessources();
        $url = (string) $url;
        
        if (empty($ressources[$url])) {
            return '';
        }

        return $ressources[$url];
    }

    /**
    * getCollection : Permet de recuperer les ressources à cacher
    *
    * @return array $collections : ressources à cacher
    */
    protected function getCollection()
    {
        $collections = array();
        $ressources = $this->getRessourceService()->getRessourceMapper()->findAll();
        foreach ($ressources as $ressource) {
            $collections[$ressource->getUrl()] = $ressource;
        }

        return $collections;
    }

    /**
     * getRessourceService : Getter pour l'instance du Service Ressource
     *
     * @return Ressource $ressourceService
     */
    private function getRessourceService()
    {
        if (null === $this->ressourceService) {
            $this->ressourceService = $this->getServiceManager()->get('playgroundcms_ressource_service');
        }

        return $this->ressourceService;
    }

    /**
     * setRessourceService : Setter pour l'instance du Service Block
     * @param  Ressource $ressourceService
     *
     * @return Ressource $ressourceService
     */
    public function setRessourceService($ressourceService)
    {
        $this->ressourceService = $ressourceService;

        return $this;
    }

}