<?php

/**
* @package : PlaygroundCMS
* @author : troger
* @since : 18/03/2014
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
    /**
    * @var Block $block bloc en cours de rendu
    */ 
    protected $block;
    
    /**
    * @var ServiceManager $serviceManager Instance du serviceManager
    */
    protected $serviceManager;

    /**
    * @var PhpRenderer $renderer Instance d'un phpRenderer qui va servir à rendre le bloc
    */
    protected $renderer;

    /**
    * @var ModuleOptions $cmsOptions
    */
    protected $cmsOptions;

    /**
    * Methode qui permet d'afficher le bloc
    */
    abstract protected function renderBlock();

    /**
    * __construct 
    * @param Block $block Bloc à rendre
    * @param ServiceManager $serviceManager
    */
    public function __construct(Block $block, ServiceManager $serviceManager)
    {
        $this->setBlock($block);
        $this->setServiceManager($serviceManager);
    }

    /**
    * renderAction : Rendre le bloc au format attendu
    * @param string $format Format dans lequel le bloc va être servi
    * @param array $parameters 
    * 
    * @return string $renderBlock Code HTML du bloc
    */
    public function renderAction($format, array $parameters)
    {
        $this->setHeaders();

        return $this->renderBlock();
    }

    /**
    * setRenderer : Setter pour le phpRenderer en prenant en compte les templates de blocs
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
    * getRenderer : Getter pour le phpRenderer avec le template du bloc concerné
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
    * render : Rendu du bloc
    * @param ViewModel $model ViewModel pour setter le template du bloc 
    *
    * @return string $render Code HTML du bloc
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
    * setBlock : Setter pour le bloc
    * @param Block block : Bloc
    *
    * @return AbstractBlockController $AbstractBlockController 
    */
    private function setBlock(Block $block)
    {
        $this->block = $block;

        return $block;
    }  

    /**
    * getBlock : Getter pour le bloc en cours de rendu
    *
    * @return Block $block: Bloc
    */
    protected function getBlock()
    {
        return $this->block;
    }

    /**
    * getRessource : Recuperer la ressource courante
    *
    * @return Ressource $ressource
    */
    public function getRessource()
    {
        return $this->getCMSOptions()->getRessource();
    }

    /**
    * getEntity : Recuperer l'entity courante
    *
    * @return *\Entity\* $entity 
    */
    public function getEntity()
    {
        return $this->getCMSOptions()->getEntity();
    }

    /**
    * setHeaders : Creation de header pour le code généré par le bloc
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
     * @param  ServiceManager $serviceManager
     *
     * @return AbstractBlockController
     */
    private function setServiceManager(ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;

        return $this;
    }

    /**
     * getCMSOptions : Getter pour les options de playgroundcms
     *
     * @return ModuleOptions $cmsOptions
     */
    protected function getCMSOptions()
    {
        if (!$this->cmsOptions) {
            $this->cmsOptions = $this->getServiceManager()->get('playgroundcms_module_options');
        }

        return $this->cmsOptions;
    }
}
