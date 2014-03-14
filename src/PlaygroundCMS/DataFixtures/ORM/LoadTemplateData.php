<?php

namespace PlaygroundCMS\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

use PlaygroundCMS\Entity\Template;

class LoadTemplateData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * Load address types
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {        

        $template = new Template();

        $template->setName('Block list template');
        $template->setFile('playground-cms/blocks/list.phtml');
        $template->setDescription('template for block list');
        $template->setBlockType('PlaygroundCMS\Blocks\BlockListController');
        
        $manager->persist($template);
        $manager->flush();


        $template = new Template();

        $template->setName('Block HTML template');
        $template->setFile('playground-cms/blocks/freeHtml.phtml');
        $template->setDescription('template for block html');
        $template->setBlockType('PlaygroundCMS\Blocks\FreeHTMLController');
        
        $manager->persist($template);
        $manager->flush();

       
    }

    public function getOrder()
    {
        return 31;
    }
}
