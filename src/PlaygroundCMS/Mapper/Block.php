<?php

/**
* @package : PlaygroundCMS
* @author : troger
* @since : 18/03/2014
*
* Classe qui permet de gérer le mapper de block
**/

namespace PlaygroundCMS\Mapper;

use Doctrine\ORM\QueryBuilder;

class Block extends EntityMapper
{

    /**
    * findBySlug : recupere des entites en fonction de filtre
    * @param string $slug slug d'un bloc à rechercher
    *
    * @return collection $blocks collection de PlaygroundCMS\Entity\Block
    */
    public function findBySlug($slug)
    {

       return $this->getEntityRepository()->findOneBy(array('slug' => $slug)); 
    }
  
    /**
    * getEntityRepository : recupere l'entite block
    *
    * @return PlaygroundCMS\Entity\Block $block
    */
    public function getEntityRepository()
    {
        if (null === $this->er) {
            $this->er = $this->em->getRepository('PlaygroundCMS\Entity\Block');
        }

        return $this->er;
    }

    /**
    * getSupportedSorts : déclaration des tris supportés par l'entity Block
    *
    * @return array $sort
    */
    public function getSupportedSorts()
    {
        
        return array(
            'name' => 'b.name'
        );
    }

    /**
    * getSupportedFilters : déclaration des filtres supportés par l'entity Block
    *
    * @return array $filters
    */
    public function getSupportedFilters()
    {
        
        return array(
            'name' => 'filterOnName',
        );
    }

    /**
    * filterOnName : Permet de filtrer sur 
    * @param QueryBuilder $query
    * @param string $name
    *
    * @return QueryBuilder $query
    */
    public function filterOnName(QueryBuilder $query, $name)
    {
        $query->andWhere("b.name LIKE :name");
        $query->setParameter('name', (string) $name);

        return $query;
    }
}