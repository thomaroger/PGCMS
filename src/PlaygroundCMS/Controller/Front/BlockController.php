<?php

namespace PlaygroundCMS\Controller\Front;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class BlockController extends AbstractActionController
{
    public function render($template, $params = array())
    {

        $params = array_replace_recursive(array(), $params);
        $this->setDefaultHeaders();
        return new ViewModel($params);
    }


    protected function setDefaultHeaders()
    {
        // Si la ressource est anonyme
        return $this->setHeaders();
    }

    protected function setHeaders()
    {
        $response = $this->getResponse();
        $response->getHeaders()->addHeaderLine('Cache-Control', 'max-age='.300)
                               ->addHeaderLine('User-Cache-Control', 'max-age='.300)
                               ->addHeaderLine('Expires', gmdate("D, d M Y H:i:s", time() + 300))
                               ->addHeaderLine('Pragma', 'cache');

    }
}
