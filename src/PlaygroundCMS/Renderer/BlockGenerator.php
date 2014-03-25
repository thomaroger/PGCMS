<?php
/**
* @package : PlaygroundCMS
* @author : troger
* @since : 18/03/2014
*
* Classe qui permet de générer les blocks
**/

namespace PlaygroundCMS\Renderer;

use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\ServiceManager\ServiceManager;
use ZfcBase\EventManager\EventProvider;
use PlaygroundCMS\Entity\Block;

class BlockGenerator extends EventProvider implements ServiceManagerAwareInterface
{
   
   /**
   * generate : Generation du block
   * @param Block $block Bloc à générer
   * @param string $format Format dans lequel on doit générer le bloc
   * @param array $parameters Tableau de paramètres
   *
   * @return string $contentBlock Contenu du bloc
   */
    public function generate(Block $block, $format = 'html', $parameters = array())
    {
        $controllerType = $block->getType();
        $controller = new $controllerType($block, $this->getServiceManager());

        return $controller->renderAction($format, $parameters);
    }

     /**
     * getServiceManager : Getter pour le serviceManager
     *
     * @return ServiceManager
     */
    private function getServiceManager()
    {
        return $this->serviceManager;
    }

     /**
     * setServiceManager : Setter pour le serviceManager
     * @param  ServiceManager $serviceManager
     *
     * @return BlockGenerator
     */
    public function setServiceManager(ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;

        return $this;
    }
}