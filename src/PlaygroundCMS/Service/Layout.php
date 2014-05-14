<?php
/**
* @package : PlaygroundCMS
* @author : troger
* @since : 03/04/2014
*
* Classe de service pour l'entite Layout
**/
namespace PlaygroundCMS\Service;

use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\ServiceManager\ServiceManager;
use ZfcBase\EventManager\EventProvider;
use PlaygroundCore\Filter\Slugify;
use PlaygroundCMS\Mapper\Layout as LayoutMapper;
use PlaygroundCMS\Entity\Layout as LayoutEntity;

class Layout extends EventProvider implements ServiceManagerAwareInterface
{

    /**
     * @var PlaygroundCMS\Mapper\Layout layoutMapper
     */
    protected $layoutMapper;

    /**
     * @var PlaygroundCMS\Mapper\Layout layoutMapper
     */
    protected $layoutZoneService;
    
    /**
     * @var PlaygroundCMS\Mapper\Layout layoutMapper
     */
    protected $zoneService;
    
    /**
     * @var PlaygroundCMS\Options\ModuleOptions cmsOptions
     */
    protected $cmsOptions;

    /**
     * @var Zend\ServiceManager\ServiceManager ServiceManager
     */
    protected $serviceManager;
        
    /**
    * create : Permet de créer un Layout
    * @param array $data : tableau de données 
    */
    public function create($data)
    {
        $layout = new LayoutEntity();
        
        $layout->setName($data['layout']['name']);
        $layout->setFile($data['layout']['file']);
        $layout->setDescription($data['layout']['description']);

        $layout = $this->getLayoutMapper()->insert($layout);

        // upload File
        $layout = $this->uploadImage($layout, $data);

        $layout = $this->addZone($layout, $data);

        $layout = $this->getLayoutMapper()->update($layout);

    }

    /**
    * update : Permet de modifer un layout
    * @param array $data : tableau de données 
    */
    public function edit($data)
    {
        $layout = $this->getLayoutMapper()->findById($data['layout']['id']);
        
        $layout->setName($data['layout']['name']);
        $layout->setFile($data['layout']['file']);
        $layout->setDescription($data['layout']['description']);

        $layout = $this->getLayoutMapper()->update($layout);

        // upload File
        if(!empty($data['files']['name'])) {
            $layout = $this->uploadImage($layout, $data);
        }

        $this->removeLayoutZone($layout);
        $layout = $this->addZone($layout, $data);

        $layout = $this->getLayoutMapper()->update($layout);

    }

    /**
    * addZone : Permet de rajouter une zone à un layout
    * @param array $data : tableau de données 
    *
    * @return Layout $layout
    */
    public function addZone($layout, $data)
    {
        $content = file_get_contents($this->getCMSOptions()->getThemeFolder().$data['layout']['file']);
        
        preg_match_all("/getZone(.*)/", $content, $matches);
        foreach ($matches[1] as $value) {
            $zoneName = (trim(trim(trim(trim($value,'?>')),"');"),"('"));
            $zone = $this->getZoneService()->findByNameOrCreate($zoneName);
            $layoutZone = $this->getLayoutZoneService()->findByLayoutZoneOrCreate($layout, $zone);
            $layout->addLayoutzone($layoutZone);
        }

        return $layout;
    }


    /**
    * removeLayoutZone : Remove les zones à un layout
    * @param Layout $layout : layout concerné
    *
    * @return boolean $boolean
    */
    public function removeLayoutZone($layout)
    {
        $layoutZones = $this->getLayoutZoneService()->getLayoutZoneMapper()->findBy(array('layout' => $layout));
        foreach ($layoutZones as $layoutZone) {
           $this->getLayoutZoneService()->getLayoutZoneMapper()->remove($layoutZone);
        }

        return true;
    }

    /**
    * uploadImage : Upload de l'image du layout
    * @param Layout $layout : layout concerné
    * @param array $data : layout concerné
    *
    * @return Layout $layout
    */
    public function uploadImage($layout, $data)
    {
         if (!empty($data['files']['tmp_name'])) {
            $path = $this->getCMSOptions()->getLayoutPath();
            
            if (!is_dir($path)) {
                mkdir($path,0777, true);
            }
            $media_url = $this->getCMSOptions()->getLayoutUrl();
            $slugify = new Slugify;
            $layoutImageName = $slugify->filter($layout->getName());
            move_uploaded_file($data['files']['tmp_name'], $path . $layout->getId() . "-" . $layoutImageName);
            $layout->setImage($media_url . $layout->getId() . "-" . $layoutImageName);
        }
        $layout = $this->getLayoutMapper()->update($layout);

        return $layout;
    }

    /**
    * checkLayout : Permet de verifier si le form est valid
    * @param array $data : tableau de données 
    *
    * @return array $result
    */
    public function checkLayout($data)
    {
        if(empty($data['layout']['name'])){
            
            return array('status' => 1, 'message' => 'Name is required', 'data' => $data);
        }

        if(empty($data['layout']['file'])){
            
            return array('status' => 1, 'message' => 'File is required', 'data' => $data);
        }

        if (!file_exists($this->getCMSOptions()->getThemeFolder().$data['layout']['file'])) {
            
            return array('status' => 1, 'message' => 'The file must be created on the filer', 'data' => $data);
        }

        return array('status' => 0, 'message' => '', 'data' => $data);
    }

    /**
    * getLayouts : Permet de récuperer les layouts sur le filer
    *
    * @return array $files layouts trouvés
    */
    public function getLayouts()
    {
        $folderTheme = "/".trim($this->getCMSOptions()->getThemeFolder(),'/');

        $files = $this->getPhtmlFiles($folderTheme, array());
        $files = $this->cleanFiles($this->getCMSOptions()->getThemeFolder(), $files);

        return $files;
    }


    /**
    * getPhtmlFiles : Permet de récuperer les layouts sur le filer
    * @param string $path : Dossier dans lequel chercher les layouts
    * @param array $files : Tableau des layouts déjà trouvés
    *
    * @return array $files layouts trouvés
    */
    public function getPhtmlFiles($path, $files)
    {
        $dir = opendir($path);
        while($item = readdir($dir)) {
            if (is_file($sub = $path.'/'.$item)) {
                if(pathinfo($path.'/'.$item, PATHINFO_EXTENSION) == "phtml") {
                    $files[] = $sub;
                }
            } else {
                if($item != "." and $item != "..") {
                    $files = $this->getPhtmlFiles($sub,$files); 
                }
            }
        }
        return $files;
    }

     /**
    * cleanFiles : Suppresion des paths inutiles
    * @param string $path : Dossier dans lequel chercher les layouts
    * @param array $files : Tableau des layouts déjà trouvés
    *
    * @return array $files layouts trouvés
    */
    public function cleanFiles($path, $files)
    {
        foreach ($files as $key => $file) {
            $files[$key] = str_replace($path, '', $files[$key]);
        }

        return $files;
    }

    /**
     * getLayoutMapper : Getter pour layoutMapper
     *
     * @return PlaygroundCMS\Mapper\Layout $layoutMapper
     */
    public function getLayoutMapper()
    {
        if (null === $this->layoutMapper) {
            $this->layoutMapper = $this->getServiceManager()->get('playgroundcms_layout_mapper');
        }

        return $this->layoutMapper;
    }

     /**
     * getLayoutZoneService : Getter pour layoutZoneService
     *
     * @return PlaygroundCMS\Mapper\LayoutZone $layoutZoneService
     */
    public function getLayoutZoneService()
    {
        if (null === $this->layoutZoneService) {
            $this->layoutZoneService = $this->getServiceManager()->get('playgroundcms_layoutZone_service');
        }

        return $this->layoutZoneService;
    }

     /**
     * getZoneService : Getter pour zoneService
     *
     * @return PlaygroundCMS\Mapper\Zone $zoneService
     */
     public function getZoneService()
    {
        if (null === $this->zoneService) {
            $this->zoneService = $this->getServiceManager()->get('playgroundcms_zone_service');
        }

        return $this->zoneService;
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
     * @return Layout
     */
    public function setServiceManager(ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;

        return $this;
    }

     /**
     * getCMSOptions : Getter pour les options de playgroundcms
     *
     * @return ModuleOptions $cmsOptions
     */
    protected function getCMSOptions()
    {
        if (!$this->cmsOptions) {
            $this->cmsOptions = $this->getServiceManager()->get('playgroundcms_module_options');
        }

        return $this->cmsOptions;
    }
}