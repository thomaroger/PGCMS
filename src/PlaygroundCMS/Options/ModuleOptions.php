<?php
/**
* @package : PlaygroundCMS\Options
* @author : troger
* @since : 18/03/2014
*
* Classe d'options pour PlaygroundCMS 
* Recuperation du templateMapResolver
* Recuperation du translator
* Recuperation des paths et url pour l'upload et la lecture des médias pour les templates et layouts
**/

namespace PlaygroundCMS\Options;

use Zend\Stdlib\AbstractOptions;
use Zend\View\Resolver\TemplateMapResolver;
use Zend\Mvc\I18n\Translator;

class ModuleOptions extends AbstractOptions
{
    const DIR_TEMPLATE = '/../../../../../../design';

    /**
     * @var TemplateMapResolver $templateMapResolver 
     */
    protected $templateMapResolver;
    
    /**
     * @var Translator $translator
     */
    protected $translator;

    /**
     * @var string $themeFolder
     */
    protected $themeFolder;

    /**
     * @var string $layout_path
     */
    protected $layout_path = 'public/media/layout/';
    
    /**
     * @var string $layout_url
     */
    protected $layout_url  =  '/media/layout/';

    /**
     * @var string $template_path
     */
    protected $template_path = 'public/media/template/';
    
    /**
     * @var string $template_url
     */
    protected $template_url  =  '/media/template/';
    
    /**
     * @var Layout $currentLayout
     */
    protected $currentLayout;

    /**
    * @var Ressource $ressource
    */
    protected $ressource;

    /**
    * @var *\Entity\* $entity 
    */
    protected $entity;

   
    public function getTemplateFolder($serviceManager)
    {
        $config = $serviceManager->get('Config');

        return __DIR__.self::DIR_TEMPLATE.'/frontend/'.$config['design']['frontend']['package'].'/'.$config['design']['frontend']['theme'].'/';
    }


    /**
    * setTemplateMapResolver : Setter pour le templateMapResolver
    * @param TemplateMapResolver $templateMapResolver
    *
    * @return ModuleOptions $moduleOptions
    */
    public function setTemplateMapResolver(TemplateMapResolver $templateMapResolver)
    {
        $this->templateMapResolver = $templateMapResolver;
    
        return $this;
    }

    /**
    * getTemplateMapResolver : Getter pour le templateMapResolver
    *
    * @return TemplateMapResolver $templateMapResolver
    */
    public function getTemplateMapResolver()
    {
        return $this->templateMapResolver;
    }

    /**
    * setTranslator : Setter pour le translator
    * @param Translator $translator
    *
    * @return ModuleOptions $moduleOptions
    */
    public function setTranslator(Translator $translator)
    {
        $this->translator = $translator;
    
        return $this;
    }

    /**
    * getTranslator : Getter pour le translator
    *
    * @return Translator $translator
    */
    public function getTranslator()
    {
        return $this->translator;
    }

    /**
    * setThemeFolder : Setter pour le themeFolder
    * @param String $themeFolder
    *
    * @return ModuleOptions $moduleOptions
    */
    public function setThemeFolder($themeFolder)
    {
        $this->themeFolder = $themeFolder;
    
        return $this;
    }

    /**
    * getThemeFolder : Getter pour le themeFolder
    *
    * @return String $themeFolder
    */
    public function getThemeFolder()
    {

        return $this->themeFolder;
    }

    /**
    * getLayoutPath : Getter pour le layout_path
    *
    * @return String $layout_path
    */
    public function getLayoutPath()
    {

        return $this->layout_path;
    }

    /**
    * getLayoutUrl : Getter pour le layout_url
    *
    * @return String $layout_url
    */
    public function getLayoutUrl()
    {

        return $this->layout_url;
    }

    /**
    * getTemplatePath : Getter pour le template_path
    *
    * @return String $template_path
    */
    public function getTemplatePath()
    {

        return $this->template_path;
    }

    /**
    * getTemplateUrl : Getter pour le template_url
    *
    * @return String $template_url
    */
    public function getTemplateUrl()
    {

        return $this->template_url;
    }

    /**
    * getLayoutPath : Getter pour le layout_path
    *
    * @return String $currentLayout
    */
    public function getCurrentLayout()
    {

        return $this->currentLayout;
    }

    /**
    * setThemeFolder : Setter pour le currentLayout
    * @param String $currentLayout
    *
    * @return ModuleOptions $moduleOptions
    */
    public function setCurrentLayout($currentLayout)
    {
        $this->currentLayout = $currentLayout;

        return $this;
    }

    /**
    * getRessource : Getter pour la ressource
    *
    * @return Ressource $ressource
    */
    public function getRessource()
    {

        return $this->ressource;
    }

    /**
    * setRessource : Setter pour le ressource
    * @param Ressource $ressource
    *
    * @return ModuleOptions $moduleOptions
    */
    public function setRessource($ressource)
    {
        $this->ressource = $ressource;

        return $this;
    }

    /**
    * getEntity : Getter pour l'entity
    *
    * @return *\Entity\* $entity 
    */
    public function getEntity()
    {

        return $this->entity;
    }

    /**
    * setEntity : Setter pour l'entity
    * @param *\Entity\* $entity 
    *
    * @return ModuleOptions $moduleOptions
    */
    public function setEntity($entity)
    {
        $this->entity = $entity;

        return $this;
    }

}