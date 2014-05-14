<?php

/**
* @package : PlaygroundCMS
* @author : troger
* @since : 11/04/2014
*
* Classe qui permet de gérer le mapper de template
**/

namespace PlaygroundCMS\Mapper;

class Template extends EntityMapper
{
    
    /**
    * getEntityRepository : recupere l'entite template
    *
    * @return PlaygroundCMS\Entity\Template $template
    */
    public function getEntityRepository()
    {
        if (null === $this->er) {
            $this->er = $this->em->getRepository('PlaygroundCMS\Entity\Template');
        }

        return $this->er;
    }
}