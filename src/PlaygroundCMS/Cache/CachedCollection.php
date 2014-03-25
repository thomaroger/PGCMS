<?php
/**
* @package : PlaygroundCMS
* @author : troger
* @since : 18/03/2014
*
* Classe qui permet de gérer le cache fichier de collections d'objets
**/

namespace PlaygroundCMS\Cache;

use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\ServiceManager\ServiceManager;
use ZfcBase\EventManager\EventProvider;

class CachedCollection extends EventProvider implements ServiceManagerAwareInterface
{
    /**
    * @var ServiceManager $serviceManager : Instance du service Manager
    */
    protected $serviceManager;
    /**
    * @var string $type : Type de l'objet à cacher, c'est à dire le nom de l'objet
    */
    protected $type;

    /**
    * getFolderCache : Getter pour recuperer le dossier dans lequel on met les fichiers de cache
    * 
    * @param string $folder : Path du dossier
    */
    private function getFolderCache()
    {
        $folder = __DIR__.'/../../../../../../data/cache/playgroundcms/';
        
        if (!file_exists($folder)) {
           mkdir($folder, 0777, true);
        }
        
        return $folder;
    }

    /**
    * clearCachedCollection : Permet de flusher (suppression du fichier) le cache d'une collection
    */
    private function clearCachedCollection()
    {
        unlink($this->getFolderCache().$this->getType());
    }

    /**
    * getCachedCollection : Permet de recuperer le contenu du cache pour une collection donnée
    * On supprime le cache fichier si le cache est expiré
    *
    * @return array $contenu : Contenu du fichier de cache
    */
    protected function getCachedCollection()
    {
        $filename = $this->getFolderCache().$this->getType();

        if (!file_exists($filename)) {
            $collections = $this->getCollection();
            $collections["cached_until"] = time() + $this->getCacheTime();
            $this->setCachedCollection(serialize($collections));
        }

        $collections = unserialize(file_get_contents($filename));
        
        if (time() > $collections["cached_until"]) {
            $this->clearCachedCollection();
        }
        unset($collections["cached_until"]);

        return $collections;
    }
    /**
    * setCachedCollection : Permet de mettre en cache une collection
    * @param string $collection : Collection sérialisée
    */
    private function setCachedCollection($collection)
    {
        $filename = $this->getFolderCache().$this->getType();
        file_put_contents($filename, (string) $collection);
    }

    /**
    * getCollection : Permet de recuperer la collection des objets à cacher
    */
    protected function getCollection()
    {
        throw new \RuntimeException(sprintf(
                'getCollection have to be defined in cached class, %s::%s() is missing.',
                $this->getType(),
                "getCollection"
            ));
        
    }

    /**
    * setType : Setter pour le type de la collection
    * @param string $type : Type de la collection
    *
    * @return CachedCollection $cachedCollection
    */
    protected function setType($type)
    {
        $this->type = (string) $type;

        return $this;
    }

    /**
    * getType : Getter pour le type de la collection
    *
    * @return string $type : Type de la collection
    */
    private function getType()
    {
        return $this->type;
    }
   
    /**
     * getServiceManager : Getter pour l'instance du Service Manager
     *
     * @return ServiceManager $serviceManager
     */
    public function getServiceManager()
    {
        return $this->serviceManager;
    }

    /**
     * setServiceManager : Setter pour l'instance du Service Manager
     * @param  ServiceManager $serviceManager
     *
     * @return CachedCollection $cachedCollection
     */
    public function setServiceManager(ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;

        return $this;
    }
}