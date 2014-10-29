<?php

/**
* @package : PlaygroundCMS
* @author : troger
* @since : 29/10/2014
*
* Classe qui permet de gérer le mapper de Revision
**/

namespace PlaygroundCMS\Mapper;

class Revision extends EntityMapper
{
    
    /**
    * getEntityRepository : recupere l'entite revision
    *
    * @return PlaygroundCMS\Entity\Revision $layout
    */
    public function getEntityRepository()
    {
        if (null === $this->er) {
            $this->er = $this->em->getRepository('PlaygroundCMS\Entity\Revision');
        }

        return $this->er;
    }
}