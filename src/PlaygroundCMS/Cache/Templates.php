<?php

namespace PlaygroundCMS\Cache;

class Templates extends CachedCollection
{
    protected $templateService;

    public function getCachedTemplates()
    {
        $this->setType('templates');

        return $this->getCachedCollection();
    }


    public function getCollection()
    {
        $collections = array();
        $templates = $this->getTemplateService()->getTemplateMapper()->findAll();
        foreach ($templates as $template) {
            $collections[] = $template;
        }

        return $collections;
    }

    public function getTemplateService()
    {
        if (null === $this->templateService) {
            $this->templateService = $this->getServiceManager()->get('playgroundcms_template_service');
        }

        return $this->templateService;
    }

    public function setTemplateService($templateService)
    {
        $this->templateService = $templateService;

        return $this;
    }
}