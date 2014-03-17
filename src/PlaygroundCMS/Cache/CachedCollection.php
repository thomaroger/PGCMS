<?php

namespace PlaygroundCMS\Cache;

use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\ServiceManager\ServiceManager;
use ZfcBase\EventManager\EventProvider;

class CachedCollection extends EventProvider implements ServiceManagerAwareInterface
{
    protected $serviceManager;

    protected $type;

    public function getFolderCache()
    {
        $folder = __DIR__.'/../../../../../../data/cache/playgroundcms/';
        
        if (!file_exists($folder)) {
           mkdir($folder, 0777, true);
        }
        
        return $folder;
    }

    public function clearCacheCollection()
    {
        unlink($this->getFolderCache().$this->getType());
    }

    public function getCachedCollection()
    {
        $filename = $this->getFolderCache().$this->getType();

        if (!file_exists($filename)) {
            $this->setCachedCollection(serialize($this->getCollection()));
        }

        return unserialize(file_get_contents($filename));
    }

    public function setCachedCollection($collection)
    {
        $filename = $this->getFolderCache().$this->getType();

        file_put_contents($filename, $collection);
    }

    public function setType($type)
    {
        $this->type = $type;

        return $type;
    }

    public function getType()
    {
        return $this->type;
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