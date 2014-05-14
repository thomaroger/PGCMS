<?php

/**
* @package : PlaygroundCMS
* @author : troger
* @since : 09/05/2014
*
* Classe qui permet de gérer le mapper des entity du CMS
**/

namespace PlaygroundCMS\Mapper;

use Doctrine\ORM\EntityManager;
use PlaygroundCMS\Options\ModuleOptions;
use Doctrine\ORM\QueryBuilder;

class EntityMapper
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;

    /**
     * @var \Doctrine\ORM\EntityRepository
     */
    protected $er;

    /**
     * @var PlaygroundCMS\Options\ModuleOptions
     */
    protected $options;


    /**
    * __construct
    * @param Doctrine\ORM\EntityManager $em
    * @param PlaygroundCMS\Options\ModuleOptions $options
    *
    */
    public function __construct(EntityManager $em, ModuleOptions $options)
    {
        $this->em      = $em;
        $this->options = $options;
    }

    /**
    * findById : recupere l'entite en fonction de son id
    * @param int $id id du layout
    *
    * @return PlaygroundCMS\Entity\Layout $layout
    */
    public function findById($id)
    {

        return $this->getEntityRepository()->find($id);
    }

    /**
    * findBy : recupere des entites en fonction de filtre
    * @param array $array tableau de filtre
    *
    * @return collection $layouts collection de PlaygroundCMS\Entity\Layout
    */
    public function findBy($array)
    {

        return $this->getEntityRepository()->findBy($array);
    }

    /**
    * findOneBy : recupere des entites en fonction de filtre
    * @param array $array tableau de filtre
    *
    * @return collection $layoutZones collection de PlaygroundCMS\Entity\LayoutZone
    */
    public function findOneBy($array)
    {

        return $this->getEntityRepository()->findOneBy($array);
    }

    /**
    * findByAndOrderBy : recupere des entites en fonction de filtre
    * @param array $by tableau de filtre
    * @param array $sortArray tableau de sort
    *
    * @return collection $layouts collection de PlaygroundCMS\Entity\Layout
    */
    public function findByAndOrderBy($by = array(), $sortArray = array())
    {

        return $this->getEntityRepository()->findBy($by, $sortArray);
    }

    /**
    * insert : insert en base une entité layout
    * @param PlaygroundCMS\Entity\Layout $layout layout
    *
    * @return PlaygroundCMS\Entity\Layout $layout
    */
    public function insert($entity)
    {

        return $this->persist($entity);
    }

    /**
    * insert : met a jour en base une entité layout
    * @param PlaygroundCMS\Entity\Layout $layout layout
    *
    * @return PlaygroundCMS\Entity\Layout $layout
    */
    public function update($entity)
    {

        return $this->persist($entity);
    }

    /**
    * persist 
    * @param PlaygroundCMS\Entity\Layout $entity layout
    *
    * @return PlaygroundCMS\Entity\Layout $layout
    */
    public function persist($entity)
    {
        $this->em->persist($entity);
        $this->em->flush();

        return $entity;
    }

    /**
    * findAll : recupere toutes les entites
    *
    * @return collection $layout collection de PlaygroundCMS\Entity\Layout
    */
    public function findAll()
    {
        
        return $this->getEntityRepository()->findAll();
    }

    /**
    * remove : supprimer une entite layout
    * @param PlaygroundCMS\Entity\Layout $layout Layout
    *
    */
    public function remove($entity)
    {
        $this->em->remove($entity);
        $this->em->flush();
    }

    /**
    * getEntityManager : Getter pour l'entity Manager
    *
    * @return Doctrine\ORM\EntityManager $em
    */
    public function getEntityManager()
    {

        return $this->em;
    }

    /**
    * getQueryBuilder : Getter pour l'entity Manager
    *
    * @return Doctrine\ORM\QueryBuilder $query
    */
    public function getQueryBuilder()
    {

        return $this->em->createQueryBuilder();
    }

    /**
    * getEntityRepositoryForEntity : Recuperer l'entité repository d'une entité
    * @param string $entity : Nom de l'entité
    *
    * @return PlaygroundCMS\Entity\Page $page 
    */
    public function getEntityRepositoryForEntity($entity)
    {

        return $this->em->getRepository($entity);
    }
}