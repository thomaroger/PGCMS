<?php
/**
* @package : PlaygroundCMS
* @author : troger
* @since : 18/03/2014
*
* Classe qui permet de surcharger le helper de gestion des plugins afin de rajouter le service manager
**/

namespace PlaygroundCMS\View;

use Zend\View\HelperPluginManager as HelperPluginManagerParent;
use Zend\ServiceManager\ServiceManager;

class HelperPluginManager extends HelperPluginManagerParent
{
     /**
     * @var Zend\ServiceManager\ServiceManage $serviceManager
     */
    protected $serviceManager;
    
    /**
     * Default set of helpers factories
     *
     * @var array
     */
    protected $factories = array(
        'flashmessenger' => 'Zend\View\Helper\Service\FlashMessengerFactory',
        'identity'       => 'Zend\View\Helper\Service\IdentityFactory',
    );
    
    /**
     * Default set of helpers
     *
     * @var array
     */
    protected $invokableClasses = array(
        'basepath'            => 'Zend\View\Helper\BasePath',
        'cycle'               => 'Zend\View\Helper\Cycle',
        'declarevars'         => 'Zend\View\Helper\DeclareVars',
        'doctype'             => 'Zend\View\Helper\Doctype', // overridden by a factory in ViewHelperManagerFactory
        'escapehtml'          => 'Zend\View\Helper\EscapeHtml',
        'escapehtmlattr'      => 'Zend\View\Helper\EscapeHtmlAttr',
        'escapejs'            => 'Zend\View\Helper\EscapeJs',
        'escapecss'           => 'Zend\View\Helper\EscapeCss',
        'escapeurl'           => 'Zend\View\Helper\EscapeUrl',
        'gravatar'            => 'Zend\View\Helper\Gravatar',
        'headlink'            => 'Zend\View\Helper\HeadLink',
        'headmeta'            => 'Zend\View\Helper\HeadMeta',
        'headscript'          => 'Zend\View\Helper\HeadScript',
        'headstyle'           => 'Zend\View\Helper\HeadStyle',
        'headtitle'           => 'Zend\View\Helper\HeadTitle',
        'htmlflash'           => 'Zend\View\Helper\HtmlFlash',
        'htmllist'            => 'Zend\View\Helper\HtmlList',
        'htmlobject'          => 'Zend\View\Helper\HtmlObject',
        'htmlpage'            => 'Zend\View\Helper\HtmlPage',
        'htmlquicktime'       => 'Zend\View\Helper\HtmlQuicktime',
        'inlinescript'        => 'Zend\View\Helper\InlineScript',
        'json'                => 'Zend\View\Helper\Json',
        'layout'              => 'Zend\View\Helper\Layout',
        'paginationcontrol'   => 'Zend\View\Helper\PaginationControl',
        'partialloop'         => 'Zend\View\Helper\PartialLoop',
        'partial'             => 'Zend\View\Helper\Partial',
        'placeholder'         => 'Zend\View\Helper\Placeholder',
        'renderchildmodel'    => 'Zend\View\Helper\RenderChildModel',
        'rendertoplaceholder' => 'Zend\View\Helper\RenderToPlaceholder',
        'serverurl'           => 'Zend\View\Helper\ServerUrl',
        'url'                 => 'Zend\View\Helper\Url',
        'viewmodel'           => 'Zend\View\Helper\ViewModel',
        'cmstranslate'        => 'PlaygroundCMS\View\Helper\CMSTranslate',
    );

     /**
     * Attempt to create an instance via an invokable class
     *
     * Overrides parent implementation by passing $creationOptions to the
     * constructor, if non-null.
     *
     * WARNING : ADD PlaygroundCMS
     * Add call to setServiceManager to acces to ServiceManager if helper add a method setServiceManager
     *
     * @param  string $canonicalName
     * @param  string $requestedName
     * @return null|\stdClass
     *
     * @throws Exception\ServiceNotCreatedException If resolved class does not exist
     */
    protected function createFromInvokable($canonicalName, $requestedName)
    {
        $instance = parent::createFromInvokable($canonicalName, $requestedName);

        if (method_exists($instance, "setServiceManager")) {
            $instance->setServiceManager($this->getServiceManager());
        }


        return $instance;
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
     * @return HelperPluginManager
     */
    public function setServiceManager(ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;

        return $this;
    }


}
