<?php

/**
* @package : PlaygroundCMS
* @author : troger
* @since : 23/03/2014
*
* Classe qui permet de gérer le mapper de ressource
**/

namespace PlaygroundCMS\Mapper;

class Ressource extends EntityMapper
{
  
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