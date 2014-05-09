<?php

/**
* @package : PlaygroundCMS
* @author : troger
* @since : 24/03/2014
*
* Classe qui permet de gérer le mapper de page
**/

namespace PlaygroundCMS\Mapper;


class Page extends EntityMapper
{
   
    /**
    * findBySlug : recupere des entites en fonction de filtre
    * @param string $slug slug d'un page à rechercher
    *
    * @return collection $pages collection de PlaygroundCMS\Entity\Page
    */
    public function findBySlug($slug)
    {

       return $this->getEntityRepository()->findOneBy(array('slug' => $slug)); 
    }

    /**
    * getEntityRepository : recupere l'entite page
    *
    * @return PlaygroundCMS\Entity\Page $page
    */
    public function getEntityRepository()
    {
        if (null === $this->er) {
            $this->er = $this->em->getRepository('PlaygroundCMS\Entity\Page');
        }

        return $this->er;
    }
}