<?php

/**
* @package : PlaygroundCMS
* @author : troger
* @since : 10/04/2014
*
* Classe qui permet de gérer le mapper de blockLayoutZone
**/

namespace PlaygroundCMS\Mapper;

use Doctrine\ORM\EntityManager;
use PlaygroundCMS\Options\ModuleOptions;
use Doctrine\ORM\QueryBuilder;
use PlaygroundCMS\Entity\BlockLayoutZone as BlockLayoutZoneEntity;

class BlockLayoutZone
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
    * @param int $id id du blockLayoutZone
    *
    * @return PlaygroundCMS\Entity\BlockLayoutZone $blockLayoutZone
    */
    public function findById($id)
    {
        
        return $this->getEntityRepository()->find($id);
    }

    /**
    * findBy : recupere des entites en fonction de filtre
    * @param array $array tableau de filtre
    *
    * @return collection $blockLayoutZones collection de PlaygroundCMS\Entity\BlockLayoutZone
    */
    public function findBy($array)
    {

        return $this->getEntityRepository()->findBy($array);
    }


    /**
    * findByAndOrderBy : recupere des entites en fonction de filtre
    * @param array $by tableau de filtre
    * @param array $sortArray tableau de sort
    *
    * @return collection $blockLayoutZones collection de PlaygroundCMS\Entity\BlockLayoutZone
    */
    public function findByAndOrderBy($by = array(), $sortArray = array())
    {

        return $this->getEntityRepository()->findBy($by, $sortArray);
    }

    /**
    * insert : insert en base une entité blockLayoutZone
    * @param PlaygroundCMS\Entity\BlockLayoutZone $blockLayoutZone blockLayoutZone
    *
    * @return PlaygroundCMS\Entity\BlockLayoutZone $blockLayoutZone
    */
    public function insert(BlockLayoutZoneEntity $entity)
    {

        return $this->persist($entity);
    }

    /**
    * insert : met a jour en base une entité blockLayoutZone
    * @param PlaygroundCMS\Entity\BlockLayoutZone $blockLayoutZone blockLayoutZone
    *
    * @return PlaygroundCMS\Entity\BlockLayoutZone $blockLayoutZone
    */
    public function update(BlockLayoutZoneEntity $entity)
    {

        return $this->persist($entity);
    }

    /**
    * persist 
    * @param PlaygroundCMS\Entity\BlockLayoutZone $entity blockLayoutZone
    *
    * @return PlaygroundCMS\Entity\BlockLayoutZone $blockLayoutZone
    */
    protected function persist(BlockLayoutZoneEntity $entity)
    {
        $this->em->persist($entity);
        $this->em->flush();

        return $entity;
    }

    /**
    * findAll : recupere toutes les entites
    *
    * @return collection $blockLayoutZone collection de PlaygroundCMS\Entity\BlockLayoutZone
    */
    public function findAll()
    {

        return $this->getEntityRepository()->findAll();
    }

    /**
    * remove : supprimer une entite blockLayoutZone
    * @param PlaygroundCMS\Entity\BlockLayoutZone $blockLayoutZone BlockLayoutZone
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
    * getEntityRepository : recupere l'entite blockLayoutZone
    *
    * @return PlaygroundCMS\Entity\BlockLayoutZone $blockLayoutZone
    */
    public function getEntityRepository()
    {
        if (null === $this->er) {
            $this->er = $this->em->getRepository('PlaygroundCMS\Entity\BlockLayoutZone');
        }

        return $this->er;
    }

    /**
    * getBlocksBelow : Permet de recuperer les blocs qui sont positionné après un bloc
    * @param BlockLayoutZone $blockLayoutZone 
    * @param int $position
    * 
    * @return array $results
    */
    public function getBlocksBelow($blockLayoutZone, $position)
    {
        $select  = " SELECT BLZ ";
        $from    = " FROM PlaygroundCMS\Entity\BlockLayoutZone BLZ";
        $where   = " WHERE BLZ.id != ".$blockLayoutZone->getId()." 
                     AND BLZ.layoutZone = ".$blockLayoutZone->getLayoutZone()->getId()."
                     AND BLZ.position >= ".$position;

        $query = $select.' '.$from.' '.$where;
        $results =  $this->em->createQuery($query)->getResult();

        return $results;
    }
}