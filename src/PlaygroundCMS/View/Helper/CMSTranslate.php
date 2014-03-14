<?php

namespace PlaygroundCMS\View\Helper;

use Zend\I18n\View\Helper\AbstractTranslatorHelper;

class CMSTranslate extends AbstractTranslatorHelper
{
    protected $serviceLocator;

    public function __invoke($message, $textDomain = null, $locale = null)
    {
        return "CMS Translate is not actif";
        $translator = $this->getTranslator();
        return $translator->__invoke($message, $textDomain, $locale);
    }

    public function getTranslator()
    {
        return $this->getServiceLocator()->get('playgroundcms_module_options')->getTranslator();
    }


    public function setServiceLocator($serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;

        return $this;
    }

    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }
}