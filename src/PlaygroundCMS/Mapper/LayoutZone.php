<?php

/**
* @package : PlaygroundCMS
* @author : troger
* @since : 24/03/2014
*
* Classe qui permet de gérer le mapper de layoutZone
**/

namespace PlaygroundCMS\Mapper;

use Doctrine\ORM\EntityManager;
use PlaygroundCMS\Options\ModuleOptions;
use Doctrine\ORM\QueryBuilder;

class LayoutZone
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
    * @param int $id id du layoutZone
    *
    * @return PlaygroundCMS\Entity\LayoutZone $layoutZone
    */
    public function findById($id)
    {
        return $this->getEntityRepository()->find($id);
    }

    /**
    * findBy : recupere des entites en fonction de filtre
    * @param array $array tableau de filtre
    *
    * @return collection $layoutZones collection de PlaygroundCMS\Entity\LayoutZone
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
    * findBySlug : recupere des entites en fonction de filtre
    * @param string $slug slug d'un layoutZone à rechercher
    *
    * @return collection $layoutZones collection de PlaygroundCMS\Entity\LayoutZone
    */
    public function findBySlug($slug)
    {
       return $this->getEntityRepository()->findOneBy(array('slug' => $slug)); 
    }

    /**
    * findByAndOrderBy : recupere des entites en fonction de filtre
    * @param array $by tableau de filtre
    * @param array $sortArray tableau de sort
    *
    * @return collection $layoutZones collection de PlaygroundCMS\Entity\LayoutZone
    */
    public function findByAndOrderBy($by = array(), $sortArray = array())
    {
        return $this->getEntityRepository()->findBy($by, $sortArray);
    }

    /**
    * insert : insert en base une entité layoutZone
    * @param PlaygroundCMS\Entity\LayoutZone $layoutZone layoutZone
    *
    * @return PlaygroundCMS\Entity\LayoutZone $layoutZone
    */
    public function insert($entity)
    {
        return $this->persist($entity);
    }

    /**
    * insert : met a jour en base une entité layoutZone
    * @param PlaygroundCMS\Entity\LayoutZone $layoutZone layoutZone
    *
    * @return PlaygroundCMS\Entity\LayoutZone $layoutZone
    */
    public function update($entity)
    {
        return $this->persist($entity);
    }

    /**
    * persist 
    * @param PlaygroundCMS\Entity\LayoutZone $entity layoutZone
    *
    * @return PlaygroundCMS\Entity\LayoutZone $layoutZone
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
    * @return collection $layoutZone collection de PlaygroundCMS\Entity\LayoutZone
    */
    public function findAll()
    {
        return $this->getEntityRepository()->findAll();
    }

    /**
    * remove : supprimer une entite layoutZone
    * @param PlaygroundCMS\Entity\LayoutZone $layoutZone LayoutZone
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

    public function getEntityRepositoryForEntity($entity)
    {
        return $this->em->getRepository($entity);
    }
}