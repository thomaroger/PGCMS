<?php
/**
* @package : PlaygroundCMS
* @author : troger
* @since : 28/03/2014
*
* Classe qui permet de loader les layouts
**/
namespace PlaygroundCMS\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

use PlaygroundCMS\Entity\Layout;

class LoadLayoutData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * load : permet de charger en base les layouts
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {        

        $layout = new Layout();

        $layout->setName('Page Layout');
        $layout->setFile('playground-cms/layout/index.phtml');
        $layout->setDescription('Layout for entity page');
        
        $manager->persist($layout);
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
