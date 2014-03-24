<?php

/**
* @package : PlaygroundCMS
* @author : troger
* @since : 24/03/2013
*
* Classe qui permet de gérer le mapper de page
**/

namespace PlaygroundCMS\Mapper;

use Doctrine\ORM\EntityManager;
use ZfcBase\Mapper\AbstractDbMapper;
use PlaygroundCMS\Options\ModuleOptions;
use Doctrine\ORM\QueryBuilder;

class Page
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
    * @param int $id id du page
    *
    * @return PlaygroundCMS\Entity\Page $pagek
    */
    public function findById($id)
    {
        return $this->getEntityRepository()->find($id);
    }

    /**
    * findBy : recupere des entites en fonction de filtre
    * @param array $array tableau de filtre
    *
    * @return collection $pageks collection de PlaygroundCMS\Entity\Page
    */
    public function findBy($array)
    {
        return $this->getEntityRepository()->findBy($array);
    }

    /**
    * findBySlug : recupere des entites en fonction de filtre
    * @param string $slug slug d'un page à rechercher
    *
    * @return collection $pageks collection de PlaygroundCMS\Entity\Page
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
    * @return collection $pageks collection de PlaygroundCMS\Entity\Page
    */
    public function findByAndOrderBy($by = array(), $sortArray = array())
    {
        return $this->getEntityRepository()->findBy($by, $sortArray);
    }

    /**
    * insert : insert en base une entité pagek
    * @param PlaygroundCMS\Entity\Page $pagek pagek
    *
    * @return PlaygroundCMS\Entity\Page $pagek
    */
    public function insert(Page $entity)
    {
        return $this->persist($entity);
    }

    /**
    * insert : met a jour en base une entité pagek
    * @param PlaygroundCMS\Entity\Page $pagek pagek
    *
    * @return PlaygroundCMS\Entity\Page $pagek
    */
    public function update(Page $entity)
    {
        return $this->persist($entity);
    }

    /**
    * persist 
    * @param PlaygroundCMS\Entity\Page $entity pagek
    *
    * @return PlaygroundCMS\Entity\Page $pagek
    */
    protected function persist(Page $entity)
    {
        $this->em->persist($entity);
        $this->em->flush();

        return $entity;
    }

    /**
    * findAll : recupere toutes les entites
    *
    * @return collection $pagek collection de PlaygroundCMS\Entity\Page
    */
    public function findAll()
    {
        return $this->getEntityRepository()->findAll();
    }

    /**
    * remove : supprimer une entite pagek
    * @param PlaygroundCMS\Entity\Page $pagek Page
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
    * getEntityRepository : recupere l'entite pagek
    *
    * @return PlaygroundCMS\Entity\Page $pagek
    */
    public function getEntityRepository()
    {
        if (null === $this->er) {
            $this->er = $this->em->getRepository('PlaygroundCMS\Entity\Page');
        }

        return $this->er;
    }

    /**
    * getSupportedSorts : déclaration des tris supportés par l'entity Page
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
    * getSupportedFilters : déclaration des filtres supportés par l'entity Page
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
        $query->where("b.name LIKE :name");
        $query->setParameter('name', (string) $name);

        return $query;
    }
}