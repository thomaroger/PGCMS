<?php
/**
* @package : PlaygroundCMS
* @author : troger
* @since : 27/10/2014
*
* Classe qui permet de gérer le controlleur de base d'une entity hormis les pages
**/
namespace PlaygroundCMS\Controller\Front;

use Zend\View\Model\ViewModel;


class AbstractEntityActionController extends AbstractActionController
{
    protected function renderEntityForExport($type)
    {
        $format = $this->getEvent()->getRouteMatch()->getParam('format');
        $response = $this->getResponse();

        $block = $this->getBlockService()->getBlockMapper()->FindOneBy(array('type' => $type,
                                                                             'isEntityDetail' => true));

        if (empty($block)) {
            $response->setStatusCode(404);

            return;
        }

        $out = $this->getBlockRendererService()
                        ->setBlock($block)
                        ->render($format);

        $response->setStatusCode(200);
        $response->setContent(utf8_decode($out)); 

        $response->getHeaders()->addHeaderLine('Access-Control-Allow-Origin', '*');
        
        return $response;
        
    }
}
