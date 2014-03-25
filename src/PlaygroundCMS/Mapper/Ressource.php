<?php

/**
* @package : PlaygroundCMS
* @author : troger
* @since : 23/03/2014
*
* Classe qui permet de gérer le mapper de ressource
**/

namespace PlaygroundCMS\Mapper;

use Doctrine\ORM\EntityManager;
use ZfcBase\Mapper\AbstractDbMapper;
use PlaygroundCMS\Options\ModuleOptions;

class Ressource
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
    * @param int $id id du ressource
    *
    * @return PlaygroundCMS\Entity\Ressource $ressourcek
    */
    public function findById($id)
    {
        return $this->getEntityRepository()->find($id);
    }

    /**
    * findBy : recupere des entites en fonction de filtre
    * @param array $array tableau de filtre
    *
    * @return collection $ressourceks collection de PlaygroundCMS\Entity\Ressource
    */
    public function findBy($array)
    {
        return $this->getEntityRepository()->findBy($array);
    }

    /**
    * findBySlug : recupere des entites en fonction de filtre
    * @param string $slug slug d'un ressource à rechercher
    *
    * @return collection $ressourceks collection de PlaygroundCMS\Entity\Ressource
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
    * @return collection $ressourceks collection de PlaygroundCMS\Entity\Ressource
    */
    public function findByAndOrderBy($by = array(), $sortArray = array())
    {
        return $this->getEntityRepository()->findBy($by, $sortArray);
    }

    /**
    * insert : insert en base une entité ressourcek
    * @param PlaygroundCMS\Entity\Ressource $ressourcek ressourcek
    *
    * @return PlaygroundCMS\Entity\Ressource $ressourcek
    */
    public function insert(Ressource $entity)
    {
        return $this->persist($entity);
    }

    /**
    * insert : met a jour en base une entité ressourcek
    * @param PlaygroundCMS\Entity\Ressource $ressourcek ressourcek
    *
    * @return PlaygroundCMS\Entity\Ressource $ressourcek
    */
    public function update(Ressource $entity)
    {
        return $this->persist($entity);
    }

    /**
    * persist 
    * @param PlaygroundCMS\Entity\Ressource $entity ressourcek
    *
    * @return PlaygroundCMS\Entity\Ressource $ressourcek
    */
    protected function persist(Ressource $entity)
    {
        $this->em->persist($entity);
        $this->em->flush();

        return $entity;
    }

    /**
    * findAll : recupere toutes les entites
    *
    * @return collection $ressourcek collection de PlaygroundCMS\Entity\Ressource
    */
    public function findAll()
    {
        return $this->getEntityRepository()->findAll();
    }

    /**
    * remove : supprimer une entite ressourcek
    * @param PlaygroundCMS\Entity\Ressource $ressourcek Ressource
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
    * getEntityRepository : recupere l'entite ressourcek
    *
    * @return PlaygroundCMS\Entity\Ressource $ressourcek
    */
    public function getEntityRepository()
    {
        if (null === $this->er) {
            $this->er = $this->em->getRepository('PlaygroundCMS\Entity\Ressource');
        }

        return $this->er;
    }

    public function getEntityRepositoryForEntity($entity)
    {
        return $this->em->getRepository($entity);
    }
    
}