<?php

namespace PlaygroundCMS\Cache;

use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\ServiceManager\ServiceManager;
use ZfcBase\EventManager\EventProvider;

class CacheCollection extends EventProvider implements ServiceManagerAwareInterface
{
    protected $serviceManager;

    public function getFolderCache()
    {
        $folder = __DIR__.'/../../../../../../cache/playgroundcms/';
        
        if (!file_exists($folder)) {
           mkdir($folder, 0777, true);
        }
        
        return $folder;
    }

    public function clearCacheCollections($type)
    {
        unlink($this->getFolderCache().$type);
    }

    public function getCachedCollections($type)
    {
        $filename = $this->getFolderCache().$type;

        if (!file_exists($filename)) {
            $this->setCachedCollections($type, serialize($this->getCollections()));
        }

        return unserialize(file_get_contents($filename));
    }

    public function setCachedCollections($type, $collection)
    {
        $filename = $this->getFolderCache().$type;

        file_put_contents($filename, $collection);
    }
   
    /**
     * Retrieve service manager instance
     *
     * @return ServiceManager
     */
    public function getServiceManager()
    {
        return $this->serviceManager;
    }

    /**
     * Set service manager instance
     *
     * @param  ServiceManager $serviceManager
     * @return User
     */
    public function setServiceManager(ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;

        return $this;
    }
}