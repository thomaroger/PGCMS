<?php
/**
* @package : PlaygroundCMS
* @author : troger
* @since : 23/03/2013
*
* Classe qui permet de gérer le cache fichier de objets de type Ressource
**/

namespace PlaygroundCMS\Cache;

use Doctrine\ORM\EntityManager;

class Ressources extends CachedCollection
{
    /**
    * @var integer CACHE_TIME : Temps de cache fichier pour les ressources
    */
    const CACHE_TIME = 60;

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
    * @param string $url : Url de la ressource
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
        $ressources = $this->getEntityManager()->getRepository('PlaygroundCMS\Entity\Ressource')->findAll();
        foreach ($ressources as $ressource) {
            $collections[$ressource->getUrl()] = $ressource;
        }

        return $collections;
    }

    /**
     * getEntityManager : Getter pour EntityManager
     *
     * @return EntityManager
     */
    private function getEntityManager()
    {

        return $this->entityManager;
    }

    /**
     * setEntityManager : Setter pour le entityManager
     * @param  EntityManager $entityManager
     *
     * @return Ressources
     */
    public function setEntityManager(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        
        return $this;
    }

    /** 
    * getCacheTime : Temps de cache du fichier
    *
    * @return int $time
    */
    protected function getCacheTime() {
        $time = self::CACHE_TIME;

        return $time; 
    }

}