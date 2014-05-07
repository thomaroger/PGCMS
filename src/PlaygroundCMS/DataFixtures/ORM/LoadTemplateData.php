<?php
/**
* @package : PlaygroundCMS
* @author : troger
* @since : 18/03/2014
*
* Classe qui permet de loader les templates
**/
namespace PlaygroundCMS\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

use PlaygroundCMS\Entity\Template;

class LoadTemplateData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * load : permet de charger en base les templates
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {        

        $template = new Template();

        $template->setName('Block list template md 4');
        $template->setFile('playground-cms/blocks/list_md_4.phtml');
        $template->setDescription('template for block list');
        $template->setBlockType('PlaygroundCMS\Blocks\BlockListController');
        
        $manager->persist($template);
        $manager->flush();



        $template = new Template();

        $template->setName('Block list template md 12');
        $template->setFile('playground-cms/blocks/list_md_12.phtml');
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

        
        $template = new Template();

        $template->setName('Partial Pagination');
        $template->setFile('playground-cms/partial/pagination.phtml');
        $template->setDescription('Partial Pagination for block list');
        $template->setIsSystem(true);
        $manager->persist($template);
        $manager->flush();

       
    }

    /**
     * getOrder : donne un ordre de priorité au chargement
     *
     * @return integer $order
     */
    public function getOrder()
    {
        return 31;
    }
}
