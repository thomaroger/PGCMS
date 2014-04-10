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
use ZfcBase\Mapper\AbstractDbMapper;
use PlaygroundCMS\Options\ModuleOptions;
use Doctrine\ORM\QueryBuilder;

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
    * @param int $id id du bloc
    *
    * @return PlaygroundCMS\Entity\Block $block
    */
    public function findById($id)
    {
        return $this->getEntityRepository()->find($id);
    }

    /**
    * findBy : recupere des entites en fonction de filtre
    * @param array $array tableau de filtre
    *
    * @return collection $blocks collection de PlaygroundCMS\Entity\Block
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
    * @return collection $blocks collection de PlaygroundCMS\Entity\Block
    */
    public function findByAndOrderBy($by = array(), $sortArray = array())
    {
        return $this->getEntityRepository()->findBy($by, $sortArray);
    }

    /**
    * insert : insert en base une entité block
    * @param PlaygroundCMS\Entity\Block $block block
    *
    * @return PlaygroundCMS\Entity\Block $block
    */
    public function insert(Block $entity)
    {
        return $this->persist($entity);
    }

    /**
    * insert : met a jour en base une entité block
    * @param PlaygroundCMS\Entity\Block $block block
    *
    * @return PlaygroundCMS\Entity\Block $block
    */
    public function update(Block $entity)
    {
        return $this->persist($entity);
    }

    /**
    * persist 
    * @param PlaygroundCMS\Entity\Block $entity block
    *
    * @return PlaygroundCMS\Entity\Block $block
    */
    protected function persist(Block $entity)
    {
        $this->em->persist($entity);
        $this->em->flush();

        return $entity;
    }

    /**
    * findAll : recupere toutes les entites
    *
    * @return collection $block collection de PlaygroundCMS\Entity\Block
    */
    public function findAll()
    {
        return $this->getEntityRepository()->findAll();
    }

    /**
    * remove : supprimer une entite block
    * @param PlaygroundCMS\Entity\Block $block Block
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
    * getEntityRepository : recupere l'entite block
    *
    * @return PlaygroundCMS\Entity\Block $block
    */
    public function getEntityRepository()
    {
        if (null === $this->er) {
            $this->er = $this->em->getRepository('PlaygroundCMS\Entity\BlockLayoutZone');
        }

        return $this->er;
    }
}