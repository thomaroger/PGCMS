<?php

/**
* @package : PlaygroundCMS
* @author : troger
* @since : 18/03/2013
*
* Classe qui permet de gérer l'affichage de base d'un bloc
**/

namespace PlaygroundCMS\Blocks;

use PlaygroundCMS\Entity\Block;
use Zend\View\Resolver;
use Zend\View\Renderer\PhpRenderer;
use Zend\View\Model\ViewModel;
use Zend\ServiceManager\ServiceManager;
use PlaygroundCMS\View\HelperPluginManager;

abstract class AbstractBlockController
{
    protected $block;
    protected $serviceManager;
    protected $renderer;

    /**
    * Methode qui permet d'afficher le bloc
    */
    abstract protected function renderBlock();

    /**
    * __construct 
    * @param ServiceManager $serviceManager
    * @param Block $block Block à rendre
    */
    public function __construct(ServiceManager $serviceManager, Block $block)
    {
        $this->setBlock($block);
        $this->setServiceManager($serviceManager);
    }

    /**
    * renderAction : Rendre le block au format attendu
    * @param string $format Format dans lequel le block va être servi
    * @param array $parameters 
    * 
    * @return string $renderBlock Code HTML du block
    */
    public function renderAction($format, array $parameters)
    {
        $this->setHeaders();

        return $this->renderBlock();
    }

    /**
    * setRenderer : Setter pour le phpRenderer en prenant en compte les templates de block
    * 
    * @return AbstractBlockController 
    */
    private function setRenderer()
    {
        $renderer = new PhpRenderer();

        $helperPluginManager = new HelperPluginManager();
        $helperPluginManager->setServiceManager($this->getServiceManager());
        $renderer->setHelperPluginManager($helperPluginManager);
        $resolver = $this->getServiceManager()->get('playgroundcms_module_options')->getTemplateMapResolver();
        $renderer->setResolver($resolver);

        $this->renderer = $renderer;

        return $this;
    }

    /**
    * getRenderer : Getter pour le phpRenderer avec le template du block concerné
    *
    * @return PhpRenderer $renderer 
    */
    private function getRenderer()
    {
        if($this->renderer === null) {
            $this->setRenderer();
        }

        return $this->renderer;
    }

    /**
    * render : Rendu du block
    * @param ViewModel $model ViewModel pour setter le template du block   
    *
    * @return string $render Code HTML du block
    */
    protected function render(ViewModel $model)
    {
        $template = $this->getTemplate();
        $model->setTemplate($template);

        return $this->getRenderer($model)->render($model);
    }

    /**
    * getTemplate : Template du bloc 
    *
    * @return Template du type "WEB" du bloc 
    */
    private function getTemplate()
    {
        $block = $this->getBlock();
        $templates = json_decode($block->getTemplateContext(), true);

        
        $template = $templates['web'];
        

        return $template;
    }  

    /**
    * getRequest : Recuperation de la request
    *
    * @return Request $request
    */
    protected function getRequest()
    {
        return $this->getServiceManager()->get('request');
    }

    /**
    * setBlock : Setter pour le block
    * @param Block block : Block
    *
    * @return AbstractBlockController $AbstractBlockController 
    */
    private function setBlock(Block $block)
    {
        $this->block = $block;

        return $block;
    }  

    /**
    * getBlock : Getter pour le block
    *
    * @return Block block : Block 
    */
    protected function getBlock()
    {
        return $this->block;
    }

    /**
    * setHeaders : Creation de header pour le code généré par le block
    * 
    */
    protected function setHeaders()
    {
        /*$response = $this->getResponse();
        $response->getHeaders()->addHeaderLine('Cache-Control', 'max-age='.'300')
                               ->addHeaderLine('User-Cache-Control', 'max-age='.'300')
                               ->addHeaderLine('Expires', gmdate("D, d M Y H:i:s", time() + 300))
                               ->addHeaderLine('Pragma', 'cache');*/
    }

     /**
     * getServiceManager : Getter pour le serviceManager
     *
     * @return ServiceManager
     */
    protected function getServiceManager()
    {
        return $this->serviceManager;
    }

    /**
     * setServiceManager : Setter pour le serviceManager
     *
     * @param  ServiceManager $serviceManager
     * @return AbstractBlockController
     */
    private function setServiceManager(ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;

        return $this;
    }
}
