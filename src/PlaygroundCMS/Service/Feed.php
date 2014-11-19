<?php
/**
* @package : PlaygroundCMS
* @author : troger
* @since : 12/04/2014
*
* Classe de service pour les feeds
**/
namespace PlaygroundCMS\Service;

use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\ServiceManager\ServiceManager;
use ZfcBase\EventManager\EventProvider;
use PlaygroundCMS\Entity\Feed as FeedEntity;

class Feed extends EventProvider implements ServiceManagerAwareInterface
{

    /**
     * @var PlaygroundCMS\Mapper\Feed FeedMapper
     */
    protected $feedMapper;


    public function createFeed($object, $title, $text = "")
    {   
        $auth = $this->getServiceManager()->get('zfcuser_auth_service');
        
        if (!$auth->hasIdentity()) {
            
            return false;
        }
        
        $user = $auth->getIdentity();

        $feed = new FeedEntity();

        $feed->setUser($user);
        $feed->setModel(get_class($object));
        $feed->setRecordId($object->getId());
        $feed->setName($title);
        $feed->setText($text);

        $feed = $this->getFeedMapper()->insert($feed);

        return $feed;
    }

    /**
     * getFeeds : Permet de recuperer les feeds
     *
     * @return array $feeds
    */
    public function getFeeds()
    {

        return $this->getFeedMapper()->findByAndOrderBy(array(), array('updated_at' => 'DESC'));
    }
    
     /**
     * getBlockMapper : Getter pour Block
     *
     * @return PlaygroundCMS\Mapper\Block $blockMapper
     */
    protected function getFeedMapper()
    {
        if (empty($this->feedMapper)) {
            $this->setFeedMapper($this->getServiceManager()->get('playgroundcms_feed_mapper'));
        }
        
        return $this->feedMapper;
    }

     /**
     * setBlockMapper : Setter pour Block
     * @param PlaygroundCMS\Mapper\Block $blockMapper
     *
     * @return Feed $this
     */
    protected function setFeedMapper($feedMapper)
    {
        $this->feedMapper = $feedMapper;

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
     * @return Feed $this
     */
    public function setServiceManager(ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;

        return $this;
    }
}