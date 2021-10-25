<?php

namespace App\DataFixtures;

use App\DataFixtures\AppFixtures;
use App\Entity\Card;
use App\Entity\Category;
use App\Entity\Deck;
use App\Entity\Language;
use App\Entity\User;
use DateTime;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $encoder;
  

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    public function load(ObjectManager $manager)
    {
        // LANGUAGES
        $french = new Language();
        $french->setName("Français")->setFlag(null);
        $manager->persist($french);
        $manager->flush();

        $english = new Language();
        $english->setName("Anglais")->setFlag(null);
        $manager->persist($english);
        $manager->flush();

        // USERS
        $admin = new User();
        $admin->setEmail("admin@flash.com")
        ->setPassword($this->encoder->encodePassword($admin, 'admin'))
        ->setRoles(['ROLE_ADMIN'])
        ->setName("Admin")
        ->setCreatedAt(new DateTime("now"))
        ->setLanguageNative($french)
        ->setLanguageLearn($english)
        ->setImage("");
        $manager->persist($admin);
        $manager->flush();

        $lulu = new User();
        $lulu->setEmail("lulu@lulu.com")
        ->setPassword($this->encoder->encodePassword($lulu, 'lulu'))
        ->setRoles(['ROLE_USER'])
        ->setName("Lulu")
        ->setCreatedAt(new DateTime("now"))
        ->setLanguageNative($french)
        ->setLanguageLearn($english)
        ->setImage("");
        $manager->persist($lulu);
        $manager->flush();

        // CARDS TYPE
        $conjuguaison = new Category();
        $conjuguaison->setName("Conjuguaison");
        $manager->persist($conjuguaison);
        $manager->flush();

        $vocabulaire = new Category();
        $vocabulaire->setName("Vocabulaire");
        $manager->persist($vocabulaire);
        $manager->flush();

        $dicton = new Category();
        $dicton->setName("Dicton");
        $manager->persist($dicton);
        $manager->flush();

    }
}