<?php

/**
* @package : PlaygroundCMS
* @author : troger
* @since : 03/04/2014
*
* Classe qui permet de gérer le mapper de zone
**/

namespace PlaygroundCMS\Mapper;

use Doctrine\ORM\EntityManager;
use PlaygroundCMS\Options\ModuleOptions;

class Zone
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
    * @param int $id id du zone
    *
    * @return PlaygroundCMS\Entity\Zone $zone
    */
    public function findById($id)
    {

        return $this->getEntityRepository()->find($id);
    }

    /**
    * findBy : recupere des entites en fonction de filtre
    * @param array $array tableau de filtre
    *
    * @return collection $zones collection de PlaygroundCMS\Entity\Zone
    */
    public function findBy($array)
    {

        return $this->getEntityRepository()->findBy($array);
    }

     /**
    * findBy : recupere des entites en fonction de filtre
    * @param array $array tableau de filtre
    *
    * @return collection $zones collection de PlaygroundCMS\Entity\Zone
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
    * @return collection $zones collection de PlaygroundCMS\Entity\Zone
    */
    public function findByAndOrderBy($by = array(), $sortArray = array())
    {

        return $this->getEntityRepository()->findBy($by, $sortArray);
    }

    /**
    * insert : insert en base une entité zone
    * @param PlaygroundCMS\Entity\Zone $zone zone
    *
    * @return PlaygroundCMS\Entity\Zone $zone
    */
    public function insert($entity)
    {

        return $this->persist($entity);
    }

    /**
    * insert : met a jour en base une entité zone
    * @param PlaygroundCMS\Entity\Zone $zone zone
    *
    * @return PlaygroundCMS\Entity\Zone $zone
    */
    public function update($entity)
    {

        return $this->persist($entity);
    }

    /**
    * persist 
    * @param PlaygroundCMS\Entity\Zone $entity zone
    *
    * @return PlaygroundCMS\Entity\Zone $zone
    */
    protected function persist($entity)
    {
        $this->em->persist($entity);
        $this->em->flush();

        return $entity;
    }

    /**
    * findAll : recupere toutes les entites
    *
    * @return collection $zone collection de PlaygroundCMS\Entity\Zone
    */
    public function findAll()
    {
        return $this->getEntityRepository()->findAll();
    }

    /**
    * remove : supprimer une entite zone
    * @param PlaygroundCMS\Entity\Zone $zone Zone
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