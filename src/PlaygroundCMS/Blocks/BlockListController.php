<?php

/**
* @package : PlaygroundCMS\Blocks
* @author : troger
* @since : 18/03/2014
*
* Classe qui permet de gérer l'affichage d'un bloc de liste de l'entité block
**/

namespace PlaygroundCMS\Blocks;

use PlaygroundCMS\Entity\Block;
use PlaygroundCMS\Mapper\Block as BlockMapper;
use Zend\View\Model\ViewModel;
use Zend\View\Renderer\PhpRenderer;
use Zend\View\Resolver;

class BlockListController extends AbstractListController
{
    /**
    * @var PlaygroundCMS\Mapper\* $blockMapper: Classe de Mapper relié à l'entité Block
    */
    protected $blockMapper;

    /**
    * {@inheritdoc}
    * renderBlock : Rendu du bloc de liste de l'entité block avec filtres, tris et pagination
    */
    protected function renderBlock()
    {
        $request = $this->getRequest();

        $block = $this->getBlock();
        $query = $this->getBlockMapper()->getQueryBuilder();
        $query = $query->select('b')->from('PlaygroundCMS\Entity\Block', 'b');

        $query = $this->addFilters($query);
        $query = $this->addSort($query);        

        list($results, $countResults) = $this->addPager($query);

        $params = array('block' => $block,
                        'results' => $results,
                        'countResults' => $countResults,
                        'uri' => $request->getUri()->getPath());

        $model = new ViewModel($params);
        
        return $this->render($model);
    }

    /**
    * __toString : Permet de decrire le bloc
    *
    * @return string $return : Block list block
    */
    public function __toString()
    {
        
        return 'Block list Block';
    }

    /**
    * getBlockMapper : Getter pour le blockMapper
    *
    * @return PlaygroundCMS\Mapper\Block $blockMapper : Classe de Mapper relié à l'entité Block
    */
    protected function getBlockMapper()
    {
        if (empty($this->blockMapper)) {
            $this->setBlockMapper($this->getServiceManager()->get('playgroundcms_block_mapper'));
        }

        return $this->blockMapper;
    }
}
