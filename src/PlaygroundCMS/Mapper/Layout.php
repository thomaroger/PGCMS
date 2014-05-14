<?php

/**
* @package : PlaygroundCMS
* @author : troger
* @since : 28/03/2014
*
* Classe qui permet de gérer le mapper de layout
**/

namespace PlaygroundCMS\Mapper;

class Layout extends EntityMapper
{
    
    /**
    * getEntityRepository : recupere l'entite layout
    *
    * @return PlaygroundCMS\Entity\Layout $layout
    */
    public function getEntityRepository()
    {
        if (null === $this->er) {
            $this->er = $this->em->getRepository('PlaygroundCMS\Entity\Layout');
        }

        return $this->er;
    }
}