<?php

/**
* @package : PlaygroundCMS
* @author : troger
* @since : 03/04/2014
*
* Classe qui permet de gérer le mapper de zone
**/

namespace PlaygroundCMS\Mapper;


class Zone  extends EntityMapper
{

    /**
    * getEntityRepository : recupere l'entite zone
    *
    * @return PlaygroundCMS\Entity\Zone $zone
    */
    public function getEntityRepository()
    {
        if (null === $this->er) {
            $this->er = $this->em->getRepository('PlaygroundCMS\Entity\Zone');
        }

        return $this->er;
    }
}