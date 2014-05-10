<?php

/**
* @package : PlaygroundCMS
* @author : troger
* @since : 23/03/2014
*
* Classe qui permet de gérer le mapper de ressource
**/

namespace PlaygroundCMS\Mapper;

use PlaygroundCMS\Entity\Ressource as RessourceEntity;

class Ressource extends EntityMapper
{
    
    /**
    * getRessourcesInAllLocales : Recuperer toutes les ressources associé à une autre ressource
    * @param Ressource $ressource 
    *
    * @return array $ressources 
    */
    public function getRessourcesInAllLocales(RessourceEntity $ressource)
    {
        $ressources = $this->findBy(array('model' => $ressource->getModel(), 'recordId' => $ressource->getRecordId()));

        return $ressources;
    }
    /**
    * getEntityRepository : recupere l'entite ressource
    *
    * @return PlaygroundCMS\Entity\Ressource $ressource
    */
    public function getEntityRepository()
    {
        if (null === $this->er) {
            $this->er = $this->em->getRepository('PlaygroundCMS\Entity\Ressource');
        }

        return $this->er;
    }
}