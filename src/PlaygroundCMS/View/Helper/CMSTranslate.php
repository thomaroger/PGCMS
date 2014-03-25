<?php

/**
* @package : PlaygroundCMS
* @author : troger
* @since : 18/03/2014
*
* Helper de traduction pour les sous templates
**/
namespace PlaygroundCMS\View\Helper;

use Zend\I18n\View\Helper\AbstractTranslatorHelper;
use Zend\Mvc\I18n\Translator;
use Zend\ServiceManager\ServiceManager;

class CMSTranslate extends AbstractTranslatorHelper
{
    /**
     * @var Zend\ServiceManager\ServiceManage $serviceManager
     */
    protected $serviceManager;
    /**
    * @var Zend\Mvc\I18n\Translator $translator
    */
    protected $translator;
    /**
    * @var string $textDomain (par défaut : default)
    */
    protected $textDomain = "default";

    /**
    * __invoke : méthode d'appel du helper
    * @param string $message : clé à traduire 
    * @param string $textDomain : textDomain dans lequel la clé devra être traduite
    * @param string $locale : locale dans laquelle la clé devra être traduite
    *
    * @param string $translate : traduction
    */
    public function __invoke($message, $textDomain = 'default', $locale = null)
    {   
        $textDomain = (string) $textDomain;

        if ($textDomain == 'default') {
            $textDomain = $this->getTranslatorTextDomain();
        }

        return $this->getPluginTranslator()->translate((string) $message, $textDomain, (string) $locale);
    }

     /**
     * setTranslatorTextDomain : Setter pour le translatorTextDomain
     * @param  string  $textDomain
     *
     * @return CMSTranslate
     */
    public function setTranslatorTextDomain($textDomain = 'default')
    {
        $this->translatorTextDomain = $textDomain;

        return $this;
    }

    /**
     * getTranslatorTextDomain : Getter pour translatorTextDomain
     *
     * @return string $translatorTextDomain
     */
    public function getTranslatorTextDomain()
    {
        return (string) $this->translatorTextDomain;
    }
    
     /**
     * getPluginTranslator : Getter pour translator
     *
     * @return Zend\Mvc\I18n\Translator $translator
     */
    private function getPluginTranslator()
    {
        if ($this->translator === null){
            $this->setPluginTranslator($this->getServiceManager()->get('playgroundcms_module_options')->getTranslator());
        }

        return $this->translator;
    }

     /**
     * setPluginTranslator : Setter pour le translator
     * @param  Zend\Mvc\I18n\Translator $translator
     *
     * @return CMSTranslate
     */
    public function setPluginTranslator(Translator $translator)
    {
        $this->translator = $translator;

        return $this;
    }

    /**
     * setServiceManager : Setter pour le serviceManager
     * @param  ServiceManager $serviceManager
     *
     * @return CMSTranslate
     */
    public function setServiceManager(ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;

        return $this;
    }

     /**
     * getServiceManager : Getter pour serviceManager
     *
     * @return ServiceManager
     */
    public function getServiceManager()
    {
        return $this->serviceManager;
    }


    /**
     * setServiceManager : Setter pour le serviceManager
     * @param  ServiceManager $serviceManager
     *
     * @return CMSTranslate
     */
    public function setServiceLocator(ServiceManager $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;

        return $this;
    }

     /**
     * getServiceManager : Getter pour serviceManager
     *
     * @return ServiceManager
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }
}