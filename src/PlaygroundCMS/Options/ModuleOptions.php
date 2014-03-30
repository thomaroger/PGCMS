<?php
/**
* @package : PlaygroundCMS\Blocks
* @author : troger
* @since : 18/03/2014
*
* Classe d'options pour PlaygroundCMS 
* Recuperation du templateMapResolver
* Recuperation du translator
**/

namespace PlaygroundCMS\Options;

use Zend\Stdlib\AbstractOptions;
use Zend\View\Resolver\TemplateMapResolver;
use Zend\Mvc\I18n\Translator;

class ModuleOptions extends AbstractOptions
{
    /**
     * @var TemplateMapResolver $templateMapResolver 
     */
    protected $templateMapResolver;
    
    /**
     * @var Translator $translator
     */
    protected $translator;


    protected $themeFolder;

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

    public function setThemeFolder($themeFolder)
    {
        $this->themeFolder = $themeFolder;
    
        return $this;
    }

  
    public function getThemeFolder()
    {
        return $this->themeFolder;
    }
}