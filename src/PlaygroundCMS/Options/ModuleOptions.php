<?php

namespace PlaygroundCMS\Options;

use Zend\Stdlib\AbstractOptions;

class ModuleOptions extends AbstractOptions
{
    protected $templateMapResolver;
    protected $translator;

    public function setTemplateMapResolver($templateMapResolver)
    {
        $this->templateMapResolver = $templateMapResolver;
    
        return $this;
    }

    public function getTemplateMapResolver()
    {
        return $this->templateMapResolver;
    }

    public function setTranslator($translator)
    {
        $this->translator = $translator;
    
        return $this;
    }

    public function getTranslator()
    {
        return $this->translator;
    }

}