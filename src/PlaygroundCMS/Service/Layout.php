<?php
/**
* @package : PlaygroundCMS
* @author : troger
* @since : 28/03/2014
*
* Classe de service pour l'entite Layout
**/
namespace PlaygroundCMS\Service;

use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\ServiceManager\ServiceManager;
use ZfcBase\EventManager\EventProvider;
use PlaygroundCMS\Mapper\Layout as LayoutMapper;
use PlaygroundCMS\Entity\Layout as LayoutEntity;

class Layout extends EventProvider implements ServiceManagerAwareInterface
{

    /**
     * @var PlaygroundCMS\Mapper\Layout layoutMapper
     */
    protected $layoutMapper;

    protected $cmsOptions;

    /**
     * @var Zend\ServiceManager\ServiceManager ServiceManager
     */
    protected $serviceManager;
    
    public function create($data)
    {
        $layout = new LayoutEntity();
        
        $layout->setName($data['layout']['name']);
        $layout->setFile($data['layout']['file']);
        $layout->setDescription($data['layout']['description']);


        $layout = $this->getLayoutMapper()->insert($layout);

        // upload File
        // Zone
        $this->addZone($layout);


    }

    public function addZone($layout)
    {
        $content = file_get_contents($this->getCMSOptions()->getThemeFolder().$data['layout']['file']);
        
        preg_match_all("/getZone(.*)/", $content, $matches);
        foreach ($matches[1] as $value) {
            $zoneName = (trim(trim(trim(trim($value,'?>')),"');"),"('"));
        }
    }

    public function checkLayout($data)
    {
        if(empty($data['layout']['name'])){
            return array('status' => 1, 'message' => 'Name required', 'data' => $data);
        }

        if (!file_exists($this->getCMSOptions()->getThemeFolder().$data['layout']['file'])) {
            return array('status' => 1, 'message' => 'The file must be created on the filer', 'data' => $data);
        }


        return array('status' => 0, 'message' => '', 'data' => $data);
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
     * setLayoutMapper : Setter pour le layoutMapper
     * @param  PlaygroundCMS\Mapper\Layout $layoutMapper
     *
     * @return Layout
     */
    private function setLayoutMapper(LayoutMapper $layoutMapper)
    {
        $this->layoutMapper = $layoutMapper;

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
     * @return Layout
     */
    public function setServiceManager(ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;

        return $this;
    }

    protected function getCMSOptions()
    {
        if (!$this->cmsOptions) {
            $this->cmsOptions = $this->getServiceManager()->get('playgroundcms_module_options');
        }

        return $this->cmsOptions;
    }
}