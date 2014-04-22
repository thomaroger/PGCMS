<?php
/**
* @package : PlaygroundCMS
* @author : troger
* @since : 18/03/2014
*
* Classe de service pour l'entite Template
**/
namespace PlaygroundCMS\Service;

use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\ServiceManager\ServiceManager;
use ZfcBase\EventManager\EventProvider;
use PlaygroundCore\Filter\Slugify;
use PlaygroundCMS\Mapper\Template as TemplateMapper;
use PlaygroundCMS\Entity\Template as TemplateEntity;

class Template extends EventProvider implements ServiceManagerAwareInterface
{

     /**
     * @var PlaygroundCMS\Mapper\Template templateMapper
     */
    protected $templateMapper;
    protected $cmsOptions;

    /**
     * @var Zend\ServiceManager\ServiceManager ServiceManager
     */
    protected $serviceManager;


    public function create($data)
    {
        $template = new TemplateEntity();
        
        $template->setName($data['template']['name']);
        $template->setFile($data['template']['file']);
        $template->setDescription($data['template']['description']);
        $template->setIsSystem(false);
        
        if (!empty($data['template']['block_type'])) {
            $template->setBlockType($data['template']['block_type']);
        }
        if (!empty($data['template']['entity'])) {
            $template->setEntity($data['template']['entity']);
        }
        if (!empty($data['template']['system'])) {
            $template->setIsSystem(true);
        }

        $template = $this->getTemplateMapper()->insert($template);

        // upload File
        $template = $this->uploadImage($template, $data);

    }

    public function edit($data)
    {
        $template = $this->getTemplateMapper()->findById($data['template']['id']);
        $template->setName($data['template']['name']);
        $template->setFile($data['template']['file']);
        $template->setDescription($data['template']['description']);
        $template->setIsSystem(false);
        
        $template->setBlockType($data['template']['block_type']);
        $template->setEntity($data['template']['entity']);
        if (!empty($data['template']['system'])) {
            $template->setIsSystem(true);
        }

        $template = $this->getTemplateMapper()->update($template);

        if(!empty($data['files']['name'])) {
            $template = $this->uploadImage($template, $data);
        }

    }

    public function uploadImage($template, $data)
    {
         if (!empty($data['files']['tmp_name'])) {
            $path = $this->getCMSOptions()->getTemplatePath();
            
            if (!is_dir($path)) {
                mkdir($path,0777, true);
            }
            $media_url = $this->getCMSOptions()->getTemplateUrl();
            $slugify = new Slugify;
            $templateImageName = $slugify->filter($template->getName());
            move_uploaded_file($data['files']['tmp_name'], $path . $template->getId() . "-" . $templateImageName);
            $template->setImage($media_url . $template->getId() . "-" . $templateImageName);
        }
        $template = $this->getTemplateMapper()->update($template);

        return $template;
    }

    public function checkTemplate($data)
    {
        if(empty($data['template']['name'])){
            
            return array('status' => 1, 'message' => 'Name is required', 'data' => $data);
        }

        if(empty($data['template']['file'])){
            
            return array('status' => 1, 'message' => 'File is required', 'data' => $data);
        }

        if (!empty($data['template']['system'])) {
            if (!empty($data['template']['block_type']) || !empty($data['template']['entity'])) {
            
                return array('status' => 1, 'message' => 'If a template is a template system, it\'s not associate to a block type or an entity', 'data' => $data);
            }
        }

        if (empty($data['template']['system']) && empty($data['template']['block_type']) && empty($data['template']['entity'])) {
            
            return array('status' => 1, 'message' => 'A template is a template system or a template associate to a block type or an entity', 'data' => $data);
        }

        if (!file_exists($this->getCMSOptions()->getThemeFolder().$data['template']['file'])) {
            
            return array('status' => 1, 'message' => 'The file must be created on the filer', 'data' => $data);
        }

        return array('status' => 0, 'message' => '', 'data' => $data);
    }


     /**
     * getTemplateMapper : Getter pour templateMapper
     *
     * @return PlaygroundCMS\Mapper\Template $templateMapper
     */
    public function getTemplateMapper()
    {
        if (null === $this->templateMapper) {
            $this->templateMapper = $this->getServiceManager()->get('playgroundcms_template_mapper');
        }

        return $this->templateMapper;
    }

      /**
     * setTemplateMapper : Setter pour le templateMapper
     *
     * @param  TemplateMapper $templateMapper
     * @return Template
     */
    public function setTemplateMapper(TemplateMapper $templateMapper)
    {
        $this->templateMapper = $templateMapper;

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
     * @return Template
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