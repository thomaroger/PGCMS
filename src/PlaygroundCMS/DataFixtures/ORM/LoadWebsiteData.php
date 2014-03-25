<?php
/**
* @package : PlaygroundCMS
* @author : troger
* @since : 21/03/2014
*
* Classe qui permet de loader les website
**/
namespace PlaygroundCMS\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

use PlaygroundCore\Entity\Website;

class LoadWebsiteData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * Load address types
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {

        $website = new Website();
        $website->setName('France');
        $website->setCode('FR');
         
        $website->setActive(true);
        $website->setDefault(1);
        $website->addLocale(
            $this->getReference('locale-fr-fr') // load the stored reference
        );
        $manager->persist($website);
        $manager->flush();
        $this->addReference('website-fr', $website);

        $website = new Website();
        $website->setName('United State');
        $website->setCode('US');
         
        $website->setActive(true);
        $website->setDefault(0);
        $website->addLocale(
            $this->getReference('locale-en-us') // load the stored reference
        );
        $manager->persist($website);
        $manager->flush();
        $this->addReference('website-us', $website);

    }

    public function getOrder()
    {
        return 33;
    }
}
