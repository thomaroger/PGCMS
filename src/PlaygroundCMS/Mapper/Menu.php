<?php

/**
* @package : PlaygroundCMS
* @author : troger
* @since : 29/07/2014
*
* Classe qui permet de gérer le mapper de menu
**/

namespace PlaygroundCMS\Mapper;


class Menu extends EntityMapper
{

    /**
    * getEntityRepository : recupere l'entite zone
    *
    * @return PlaygroundCMS\Entity\Zone $zone
    */
    public function getEntityRepository()
    {
        if (null === $this->er) {
            $this->er = $this->em->getRepository('PlaygroundCMS\Entity\Menu');
        }

        return $this->er;
    }
}