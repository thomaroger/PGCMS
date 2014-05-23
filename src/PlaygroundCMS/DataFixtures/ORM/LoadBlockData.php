<?php
/**
* @package : PlaygroundCMS
* @author : troger
* @since : 18/03/2014
*
* Classe qui permet de loader les blocs
**/
namespace PlaygroundCMS\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

use PlaygroundCMS\Entity\Block;

class LoadBlockData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * load : permet de charger en base les blocs
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {        

       
        $block = new Block();

        $block->setName("HTML Header");
        $block->setType('PlaygroundCMS\Blocks\FreeHTMLController');

        $configuration = array('html' => '<div class="navbar navbar-inverse navbar-fixed-top" role="navigation"><div class="container"><div class="navbar-header"><button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse"><span class="sr-only">Toggle navigation</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button><a class="navbar-brand" href="/">PGCMS</a></div><div class="navbar-collapse collapse"><form class="navbar-form navbar-right" role="form"><div class="form-group"><input type="text" placeholder="Email" class="form-control"></div><div class="form-group"><input type="password" placeholder="Password" class="form-control"></div><button type="submit" class="btn btn-success">Sign in</button></form></div></div></div>');
        $block->setConfiguration(json_encode($configuration));
        $block->setSlug("block-html-header");
        $template = array('web' => "playground-cms/blocks/free_html.phtml");
        $block->setTemplateContext(json_encode($template));
        $block->setIsGallery(true);
        
        $manager->persist($block);
        $manager->flush();
      
    }

    /**
     * getOrder : donne un ordre de priorité au chargement
     *
     * @return integer $order
     */
    public function getOrder()
    {
        return 30;
    }
}
