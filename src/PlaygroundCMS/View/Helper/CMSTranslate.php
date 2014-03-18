<?php

namespace PlaygroundCMS\View\Helper;

use Zend\I18n\View\Helper\AbstractTranslatorHelper;

class CMSTranslate extends AbstractTranslatorHelper
{
    protected $serviceManager;
    protected $translator;
    protected $textDomain = "default";

    public function __invoke($message, $textDomain = 'default', $locale = null)
    {
        $translator = $this->getPluginTranslator();
        if ($textDomain == 'default') {
            $textDomain = $this->getTranslatorTextDomain();
        }
        return $translator->translate($message, $textDomain, $locale);
    }

    /**
     * Set translation text domain
     *
     * @param  string $textDomain
     * @return AbstractTranslatorHelper
     */
    public function setTranslatorTextDomain($textDomain = 'default')
    {
        $this->translatorTextDomain = $textDomain;
        return $this;
    }

    /**
     * Return the translation text domain
     *
     * @return string
     */
    public function getTranslatorTextDomain()
    {
        return $this->translatorTextDomain;
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