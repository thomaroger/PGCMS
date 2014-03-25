<?php
/**
* @package : PlaygroundCMS
* @author : troger
* @since : 25/03/2014
*
* Classe qui permet de loader les templates
**/
namespace PlaygroundCms\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

use PlaygroundUser\Entity\User;
use Zend\Crypt\Password\Bcrypt;

class LoadUserData extends AbstractFixture implements OrderedFixtureInterface
{
   /**
     * load : permet de charger en base différents utilisateurs
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setFirstname('Thomas');
        $user->setLastname('ROGER');
        $user->setUsername('troger');
        $user->setEmail('thomas.roger@adfab.fr');
        $user->setState(1);

        $newPass = 'troger';

        $bcrypt = new Bcrypt;
        $bcrypt->setCost(14);

        $pass = $bcrypt->create($newPass);
        $user->setPassword($pass);

        $user->addRole(
            $this->getReference('admin-role') // load the stored reference
        );

        $manager->persist($user);

        $manager->flush();
    }

    public function getOrder()
    {
        return 50;
    }
}
