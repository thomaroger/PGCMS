<?php

namespace PlaygroundCMS\View\Helper;

use Zend\I18n\View\Helper\AbstractTranslatorHelper;

class CMSTranslate extends AbstractTranslatorHelper
{
    protected $serviceManager;
    protected $translator;

    public function __invoke($message, $textDomain = null, $locale = null)
    {
        $translator = $this->getPluginTranslator();
        return $translator->translate($message, $textDomain, $locale);
    }

    public function setTranslatorTextDomain($textDomain = 'default')
    {
        //parent::setTranslatorTextDomain($textDomain);
    }

    public function getPluginTranslator()
    {
        if ($this->translator === null){
            $this->setPluginTranslator($this->getServiceManager()->get('playgroundcms_module_options')->getTranslator());
        }
        return $this->translator;
    }

    public function setPluginTranslator($translator)
    {
        $this->translator = $translator;

        return $this;
    }


    public function setServiceManager($serviceManager)
    {
        $this->serviceManager = $serviceManager;

        return $this;
    }

    public function getServiceManager()
    {
        return $this->serviceManager;
    }
}