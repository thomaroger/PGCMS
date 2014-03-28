<?php
/**
* @package : PlaygroundCMS
* @author : troger
* @since : 21/03/2014
*
* Classe qui permet de loader les pages
**/
namespace PlaygroundCMS\DataFixtures\ORM;

use DateTime;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use PlaygroundCMS\Entity\Page;
use PlaygroundCMS\Security\Credential;

class LoadPageData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * load : permet de charger en base les pages
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {        

        $page = new Page();
        $page->setIsWeb(1);
        $page->setIsMobile(1);
        $page->setStatus(1);

        $page->setSecurityContext(Credential::SECURITY_ANONYMOUS);
        $template = array('web' => "playground-cms/page/index.phtml");
        $page->setLayoutContext(json_encode($template));

        $startDate = DateTime::createFromFormat('d/m/Y H:i:s', '01/01/2014 00:00:00');
        $page->setStartDate($startDate);
        $endDate = DateTime::createFromFormat('d/m/Y H:i:s', '01/01/2029 00:00:00');
        $page->setEndDate($endDate);
        $page->setTranslatableLocale('fr_FR');
        $page->setTitle('Index');
        $page->setTitleMeta('PGCMS - Index');
        $page->setKeywordMeta('PGCMS, Index');
        $page->setDescriptionMeta('PGCMS - Index');

        $manager->persist($page);
        $manager->flush();

        $page = $manager->find("PlaygroundCMS\Entity\Page", $page->getId());
        $page->setTranslatableLocale('en_US');
        $page->setTitle('Home');
        $page->setTitleMeta('PGCMS - Home');
        $page->setKeywordMeta('PGCMS, Home');
        $page->setDescriptionMeta('PGCMS - Home');

        $manager->persist($page);
        $manager->flush();

        $page->createRessourceFromFixtures($manager);


        sleep(1);
        
        $page = new Page();
        $page->setIsWeb(1);
        $page->setIsMobile(1);
        $page->setStatus(1);

        $page->setSecurityContext(Credential::SECURITY_ANONYMOUS);
        $template = array('web' => "playground-cms/page/index.phtml");
        $page->setLayoutContext(json_encode($template));

        $startDate = DateTime::createFromFormat('d/m/Y H:i:s', '01/02/2014 00:00:00');
        $page->setStartDate($startDate);
        $endDate = DateTime::createFromFormat('d/m/Y H:i:s', '01/01/2029 00:00:00');
        $page->setEndDate($endDate);
        $page->setTranslatableLocale('fr_FR');
        $page->setTitle('Sommaire');
        $page->setTitleMeta('PGCMS - Sommaire');
        $page->setKeywordMeta('PGCMS, Sommaire');
        $page->setDescriptionMeta('PGCMS - Sommaire');

        $manager->persist($page);
        $manager->flush();

        $page = $manager->find("PlaygroundCMS\Entity\Page", $page->getId());
        $page->setTranslatableLocale('en_US');
        $page->setTitle('Summary');
        $page->setTitleMeta('PGCMS - Summary');
        $page->setKeywordMeta('PGCMS, Summary');
        $page->setDescriptionMeta('PGCMS - Summary');

        $manager->persist($page);
        $manager->flush();

        $page->createRessourceFromFixtures($manager);
    }

    /**
     * getOrder : donne un ordre de priorité au chargement
     *
     * @return integer $order
     */
    public function getOrder()
    {
        return 34;
    }
}
