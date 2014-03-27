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
     * load : permet de charger en base différents types de blocs
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {        

        $block = new Block();

        $block->setName("HTML Hello World");
        $block->setType('PlaygroundCMS\Blocks\FreeHTMLController');

        $configuration = array('html' => 'Hello World');
        $block->setConfiguration(json_encode($configuration));
        $block->setSlug("block-html-hello-world");
        $template = array('web' => "playground-cms/blocks/freeHtml.phtml");
        $block->setTemplateContext(json_encode($template));
        
        $manager->persist($block);
        $manager->flush();

        sleep(1);
        
        $block = new Block();

        $block->setName("HTML Form");
        $block->setType('PlaygroundCMS\Blocks\FreeHTMLController');

        $configuration = array('html' => '<div class="navbar-collapse collapse">
          <form class="navbar-form navbar-right" role="form">
            <div class="form-group">
              <input type="text" placeholder="Email" class="form-control">
            </div>
            <div class="form-group">
              <input type="password" placeholder="Password" class="form-control">
            </div>
            <button type="submit" class="btn btn-success">Sign in</button>
          </form>
        </div>');
        $block->setConfiguration(json_encode($configuration));
        $block->setSlug("block-html-form");
        $template = array('web' => "playground-cms/blocks/freeHtml.phtml");
        $block->setTemplateContext(json_encode($template));
        
        $manager->persist($block);
        $manager->flush();

        sleep(1);
        
        $block = new Block();

        $block->setName("HTML Content 1");
        $block->setType('PlaygroundCMS\Blocks\FreeHTMLController');

        $configuration = array('html' => '<p>This is a template for a simple marketing or informational website. It includes a large callout called a jumbotron and three supporting pieces of content. Use it as a starting point to create something more unique.</p>
        <p><a class="btn btn-primary btn-lg" role="button">Learn more &raquo;</a></p>');
        $block->setConfiguration(json_encode($configuration));
        $block->setSlug("block-html-content-1");
        $template = array('web' => "playground-cms/blocks/freeHtml.phtml");
        $block->setTemplateContext(json_encode($template));
        
        $manager->persist($block);
        $manager->flush();

        sleep(1);
        
        $block = new Block();

        $block->setName("HTML Content 2");
        $block->setType('PlaygroundCMS\Blocks\FreeHTMLController');

        $configuration = array('html' => '<p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
          <p><a class="btn btn-default" href="#" role="button">View details &raquo;</a></p>');
        $block->setConfiguration(json_encode($configuration));
        $block->setSlug("block-html-content-2");
        $template = array('web' => "playground-cms/blocks/freeHtml.phtml");
        $block->setTemplateContext(json_encode($template));
        
        $manager->persist($block);
        $manager->flush();

        sleep(1);
        
        $block = new Block();
        
        $block->setName("List block");
        $block->setType('PlaygroundCMS\Blocks\BlockListController');

        $configuration = array();
        $block->setConfiguration(json_encode($configuration));
        $block->setSlug("block-list-block");
        $template = array('web' => "playground-cms/blocks/list.phtml");
        $block->setTemplateContext(json_encode($template));
        
        $manager->persist($block);
        $manager->flush();

        sleep(1);
        
        $block = new Block();
        
        $block->setName("List block with Filters (Name like '%HTML%')");
        $block->setType('PlaygroundCMS\Blocks\BlockListController');

        $configuration = array('filters' => array('name' => '%HTML%'));
        $block->setConfiguration(json_encode($configuration));
        $block->setSlug("block-list-block-filters");
        $template = array('web' => "playground-cms/blocks/list.phtml");
        $block->setTemplateContext(json_encode($template));
        
        $manager->persist($block);
        $manager->flush();

        sleep(1);
        
        $block = new Block();
        
        $block->setName("List block with Sort (Name DESC)");
        $block->setType('PlaygroundCMS\Blocks\BlockListController');

        $configuration = array('sort' => array('field'=> 'name', 'direction' => 'DESC'));
        $block->setConfiguration(json_encode($configuration));
        $block->setSlug("block-list-block-sort");
        $template = array('web' => "playground-cms/blocks/list.phtml");
        $block->setTemplateContext(json_encode($template));
        
        $manager->persist($block);
        $manager->flush();

        sleep(1);
        
        $block = new Block();
        
        $block->setName("List block with Filter, Sort and Pager (Name like '%HTML%') (Name DESC) (2 blocks per page, limit result to 5 blocks)");
        $block->setType('PlaygroundCMS\Blocks\BlockListController');

        $configuration = array('filters' => array('name' => '%HTML%'), 'sort' => array('field'=> 'name', 'direction' => 'DESC'), 'pagination' => array('max_per_page' => 2, 'limit' => 5));
        $block->setConfiguration(json_encode($configuration));
        $block->setSlug("block-list-block-filters-sorts-pagers");
        $template = array('web' => "playground-cms/blocks/list.phtml");
        $block->setTemplateContext(json_encode($template));
        
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
