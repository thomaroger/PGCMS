<?php

/**
* @package : PlaygroundCMS
* @author : troger
* @since : 18/11/2014
*
* Classe qui permet de gérer le mapper de feed
**/

namespace PlaygroundCMS\Mapper;

class Feed extends EntityMapper
{
    
    /**
    * getEntityRepository : recupere l'entite layout
    *
    * @return PlaygroundCMS\Entity\Layout $layout
    */
    public function getEntityRepository()
    {
        if (null === $this->er) {
            $this->er = $this->em->getRepository('PlaygroundCMS\Entity\Feed');
        }

        return $this->er;
    }
}