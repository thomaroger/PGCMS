<?php
/**
* @package : PlaygroundCMS
* @author : troger
* @since : 29/10/2014
*
* Classe de service pour l'entite Template
**/
namespace PlaygroundCMS\Service;

use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\ServiceManager\ServiceManager;
use ZfcBase\EventManager\EventProvider;
use PlaygroundCMS\Mapper\Revision as RevisionMapper;
use PlaygroundCMS\Entity\Revision as RevisionEntity;

class Revision extends EventProvider implements ServiceManagerAwareInterface
{

     /**
     * @var PlaygroundCMS\Mapper\Template templateMapper
     */
    protected $revisionMapper;


    /**
     * @var Zend\ServiceManager\ServiceManager ServiceManager
     */
    protected $serviceManager;

    /**
    * create : Permet de créer une revision
    * @param array $data : tableau de données 
    *
    */
    public function createRevision($object)
    {
        if(!method_exists($object, "allowRevision")){

            return;
        }

        if(!$object->allowRevision()){

            return;
        }
        $count = 0;

        $filters = array('type'=>get_class($object), 'objectId' =>$object->getId());

        $revisions = $this->getRevisionMapper()->findByAndOrderBy($filters, array('id' => 'DESC'));
        $count = count($revisions) + 1;

        $revision = new RevisionEntity();
        $revision->setType(get_class($object));
        $revision->setObjectId($object->getId());
        $revision->setObject(serialize($object));
        $revision->setRevision($count);
        $this->getRevisionMapper()->insert($revision);
    }

     /**
     * getRevisionMapper : Getter pour templateMapper
     *
     * @return PlaygroundCMS\Mapper\Template $templateMapper
     */
    public function getRevisionMapper()
    {
        if (null === $this->revisionMapper) {
            $this->revisionMapper = $this->getServiceManager()->get('playgroundcms_revision_mapper');
        }

        return $this->revisionMapper;
    }

      /**
     * setTemplateMapper : Setter pour le templateMapper
     *
     * @param  TemplateMapper $templateMapper
     * @return Revision
     */
    public function setRevisionMapper(RevisionMapper $revisionMapper)
    {
        $this->revisionMapper = $revisionMapper;

        return $this;
    }

     /**
     * getServiceManager : Getter pour serviceManager
     *
     * @return ServiceManager
     */
    public function getServiceManager()
    {
        return $this->serviceManager;
    }

    /**
     * setServiceManager : Setter pour le serviceManager
     * @param  ServiceManager $serviceManager
     *
     * @return Revision
     */
    public function setServiceManager(ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;

        return $this;
    }
}