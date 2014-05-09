<?php

/**
* @package : PlaygroundCMS
* @author : troger
* @since : 24/03/2014
*
* Classe qui permet de gérer le mapper de layoutZone
**/

namespace PlaygroundCMS\Mapper;

class LayoutZone extends EntityMapper
{
    
    /**
    * getEntityRepository : recupere l'entite layoutZone
    *
    * @return PlaygroundCMS\Entity\LayoutZone $layoutZone
    */
    public function getEntityRepository()
    {
        if (null === $this->er) {
            $this->er = $this->em->getRepository('PlaygroundCMS\Entity\LayoutZone');
        }

        return $this->er;
    }
}