<?php

namespace PlaygroundCMS\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

use PlaygroundCMS\Entity\Block;

class LoadBlockData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * Load address types
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {        

        $block = new Block();

        $block->setName("Block Free HTML Example");
        $block->setType('PlaygroundCMS\Blocks\FreeHTMLController');

        $configuration = array('html' => 'Hello World');
        $block->setConfiguration(json_encode($configuration));
        $block->setSlug("Block-Free-HTML-Example");
        $template = array('web' => "playground-cms/blocks/freeHtml.phtml");
        $block->setTemplateContext(json_encode($template));
        
        $manager->persist($block);
        $manager->flush();

    }

    public function getOrder()
    {
        return 30;
    }
}
