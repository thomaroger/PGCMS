<?php
/**
* @package : PlaygroundCMS
* @author : troger
* @since : 21/03/2013
*
* Classe qui permet de loader les locales
**/
namespace PlaygroundCMS\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use PlaygroundCore\Entity\Locale;

class LoadLocaleData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * Load address types
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $locale = new Locale();
        $locale->setName('French');
        $locale->setLocale('fr_FR');
        $locale->setActiveFront(1);
        $locale->setActiveBack(1);
        $manager->persist($locale);
        $manager->flush();
        $this->addReference('locale-fr-fr', $locale);

        $locale = new Locale();
        $locale->setName('English');
        $locale->setLocale('en_US');
        $locale->setActiveFront(1);
        $locale->setActiveBack(1);
        $manager->persist($locale);
        $manager->flush();
        $this->addReference('locale-en-us', $locale);        
    }

    public function getOrder()
    {
        return 32;
    }
}