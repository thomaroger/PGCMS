<?php

namespace PlaygroundCMS\Mapper;

use Doctrine\ORM\EntityManager;
use ZfcBase\Mapper\AbstractDbMapper;

use PlaygroundCMS\Options\ModuleOptions;

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
    * findBySlug : recupere une entity Template en fonction d'un slug
    * @param string $slug slug
    *
    * @return PlaygroundCMS\Entity\Template $template
    */
    public function findBySlug($slug)
    {

       return $this->getEntityRepository()->findOneBy(array('slug' => (string) $slug)); 
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
    public function insert(Template $entity)
    {
        return $this->persist($entity);
    }

    /**
    * update : met a jour en base une entité template
    * @param PlaygroundCMS\Entity\Template $template template
    *
    * @return PlaygroundCMS\Entity\Template $template
    */
    public function update(Template $entity)
    {
        return $this->persist($entity);
    }

    /**
    * insert 
    * @param PlaygroundCMS\Entity\Template $entity template
    *
    * @return PlaygroundCMS\Entity\Template $template
    */
    protected function persist(Template $entity)
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
    public function remove(Template $entity)
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