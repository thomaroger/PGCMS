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
    * @param int $id id de la company
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

    public function findBySlug($slug)
    {

       return $this->getEntityRepository()->findOneBy(array('slug' => $slug)); 
    }

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
    * insert : met a jour en base une entité template
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

    public function getEntityManager(){

        return $this->em;
    }

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