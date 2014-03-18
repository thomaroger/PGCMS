<?php
/**
* @package : PlaygroundCMS
* @author : troger
* @since : 18/03/2013
*
* Classe qui permet de gérer le cache fichier de objets de type Templates
**/
namespace PlaygroundCMS\Cache;
use PlaygroundCMS\Service\Template;

class Templates extends CachedCollection
{   
    /**
    * @var Template $templateService : Instance du service de template
    */
    protected $templateService;

    /**
    * getCachedTemplates : Recuperation des templates cachés
    *
    * @return array $templates : Templates qui sont cachés
    */
    public function getCachedTemplates()
    {
        $this->setType('templates');

        return $this->getCachedCollection();
    }

    /**
    * getCollection : Permet de recuperer les templates à cacher
    *
    * @return array $collections : Templates à cacher
    */
    protected function getCollection()
    {
        $collections = array();
        $templates = $this->getTemplateService()->getTemplateMapper()->findAll();
        foreach ($templates as $template) {
            $collections[] = $template;
        }

        return $collections;
    }

    /**
     * getTemplateService : Getter pour l'instance du Service Template
     *
     * @return Template $templateService
     */
    private function getTemplateService()
    {
        if (null === $this->templateService) {
            $this->setTemplateService($this->getServiceManager()->get('playgroundcms_template_service'));
        }

        return $this->templateService;
    }

    /**
     * setTemplateService : Setter pour l'instance du Service Template
     * @param  Template $templateService
     *
     * @return Templates $templates
     */
    private function setTemplateService(Template $templateService)
    {
        $this->templateService = $templateService;

        return $this;
    }
}