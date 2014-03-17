<?php

namespace PlaygroundCMS\Cache;

class Templates extends CacheCollection
{
    protected $templateService;

    public function getCachedTemplates()
    {
        return $this->getCachedCollections('templates');
    }


    public function getCollections()
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