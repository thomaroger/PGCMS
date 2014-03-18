<?php
/**
* @package : PlaygroundCMS\Blocks
* @author : troger
* @since : 18/03/2013
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
    protected $templateMapResolver;
    protected $translator;


    public function setTemplateMapResolver(TemplateMapResolver $templateMapResolver)
    {
        $this->templateMapResolver = $templateMapResolver;
    
        return $this;
    }

    public function getTemplateMapResolver()
    {
        return $this->templateMapResolver;
    }

    public function setTranslator(Translator $translator)
    {
        $this->translator = $translator;
    
        return $this;
    }

    public function getTranslator()
    {
        return $this->translator;
    }

}