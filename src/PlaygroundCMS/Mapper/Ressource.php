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
    * @return PlaygroundCMS\Entity\Ressource $ressource
    */
    public function findById($id)
    {

        return $this->getEntityRepository()->find($id);
    }

    /**
    * findBy : recupere des entites en fonction de filtre
    * @param array $array tableau de filtre
    *
    * @return collection $ressources collection de PlaygroundCMS\Entity\Ressource
    */
    public function findBy($array)
    {

        return $this->getEntityRepository()->findBy($array);
    }

    /**
    * findOneBy : recupere des entites en fonction de filtre
    * @param array $array tableau de filtre
    *
    * @return collection $ressources collection de PlaygroundCMS\Entity\Ressource
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
    * @return collection $ressources collection de PlaygroundCMS\Entity\Ressource
    */
    public function findByAndOrderBy($by = array(), $sortArray = array())
    {

        return $this->getEntityRepository()->findBy($by, $sortArray);
    }

    /**
    * insert : insert en base une entité ressource
    * @param PlaygroundCMS\Entity\Ressource $ressource ressource
    *
    * @return PlaygroundCMS\Entity\Ressource $ressource
    */
    public function insert($entity)
    {

        return $this->persist($entity);
    }

    /**
    * insert : met a jour en base une entité ressource
    * @param PlaygroundCMS\Entity\Ressource $ressource ressource
    *
    * @return PlaygroundCMS\Entity\Ressource $ressource
    */
    public function update($entity)
    {

        return $this->persist($entity);
    }

    /**
    * persist 
    * @param PlaygroundCMS\Entity\Ressource $entity ressource
    *
    * @return PlaygroundCMS\Entity\Ressource $ressource
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
    * @return collection $ressource collection de PlaygroundCMS\Entity\Ressource
    */
    public function findAll()
    {

        return $this->getEntityRepository()->findAll();
    }

    /**
    * remove : supprimer une entite ressource
    * @param PlaygroundCMS\Entity\Ressource $ressource Ressource
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
    * getEntityRepository : recupere l'entite ressource
    *
    * @return PlaygroundCMS\Entity\Ressource $ressource
    */
    public function getEntityRepository()
    {
        if (null === $this->er) {
            $this->er = $this->em->getRepository('PlaygroundCMS\Entity\Ressource');
        }

        return $this->er;
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