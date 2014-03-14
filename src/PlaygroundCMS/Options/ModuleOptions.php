<?php

namespace PlaygroundCMS\Options;

use Zend\Stdlib\AbstractOptions;

class ModuleOptions extends AbstractOptions
{
    protected $templateMapResolver;
    
    public function setTemplateMapResolver($templateMapResolver)
    {
        $this->templateMapResolver = $templateMapResolver;
        return $this;
    }

    public function getTemplateMapResolver()
    {
        return $this->templateMapResolver;
    }
}