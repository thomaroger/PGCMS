<?php

/**
* @package : PlaygroundCMS
* @author : troger
* @since : 11/04/2014
*
* Classe qui permet de gérer le mapper de template
**/

namespace PlaygroundCMS\Mapper;

use Doctrine\ORM\EntityManager;
use PlaygroundCMS\Options\ModuleOptions;
use PlaygroundCMS\Entity\Template as TemplateEntity;

class Template
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
     * @var \Citroen\Options\ModuleOptions
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
    * @param int $id id du template
    *
    * @return PlaygroundCMS\Entity\Template $template
    */
    public function findById($id)
    {

        return $this->getEntityRepository()->find($id);
    }
     
    /**
    * findBy : recupere des entites en fonction de filtre
    * @param array $array tableau de filtre
    *
    * @return collection $templates collection de PlaygroundCMS\Entity\Template
    */
    public function findBy($array)
    {

        return $this->getEntityRepository()->findBy($array);
    }

    /**
    * findByAndOrderBy : recupere des entites en fonction de filtre et d'un sort
    * @param array $by tableau de filtre
    * @param array $sortArray tableau de sort
    *
    * @return collection $templates collection de PlaygroundCMS\Entity\Template
    */
    public function findByAndOrderBy($by = array(), $sortArray = array())
    {

        return $this->getEntityRepository()->findBy($by, $sortArray);
    }

    /**
    * insert : insert en base une entité template
    * @param PlaygroundCMS\Entity\Template $template template
    *
    * @return PlaygroundCMS\Entity\Template $template
    */
    public function insert($entity)
    {

        return $this->persist($entity);
    }

    /**
    * update : met a jour en base une entité template
    * @param PlaygroundCMS\Entity\Template $template template
    *
    * @return PlaygroundCMS\Entity\Template $template
    */
    public function update($entity)
    {

        return $this->persist($entity);
    }

    /**
    * insert 
    * @param PlaygroundCMS\Entity\Template $entity template
    *
    * @return PlaygroundCMS\Entity\Template $template
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
    * @return collection $template collection de PlaygroundCMS\Entity\Template
    */
    public function findAll()
    {

        return $this->getEntityRepository()->findAll();
    }

     /**
    * remove : supprimer une entite template
    * @param PlaygroundCMS\Entity\Template $template Template
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
    * getEntityRepository : recupere l'entite template
    *
    * @return PlaygroundCMS\Entity\Template $template
    */
    public function getEntityRepository()
    {
        if (null === $this->er) {
            $this->er = $this->em->getRepository('PlaygroundCMS\Entity\Template');
        }

        return $this->er;
    }
}