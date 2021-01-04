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
        $french->setName("Français")->setFlag("flags/france.png
        ");
        $manager->persist($french);
        $manager->flush();

        $english = new Language();
        $english->setName("Anglais")->setFlag("flags/uk.png
        ");
        $manager->persist($english);
        $manager->flush();

        $finnois = new Language();
        $finnois->setName("Finnois")->setFlag("flags/finland.png
        ");
        $manager->persist($finnois);
        $manager->flush();

        //CATEGORIES
        $vocabulaire = new Category();
        $vocabulaire->setName("Vocabulaire");
        $manager->persist($vocabulaire);
        $manager->flush();

        $dicton = new Category();
        $dicton->setName("Dicton");
        $manager->persist($dicton);
        $manager->flush();
        
        // USERS
        $admin = new User();
        $admin->setEmail("charlotte_sch@icloud.com")
        ->setPassword($this->encoder->encodePassword($admin, 'flashcards'))
        ->setRoles(['ROLE_ADMIN'])
        ->setName("Admin")
        ->setCreatedAt(new DateTime("now"))
        ->setLanguageNative($french)
        ->setLanguageLearn($english)
        ->setImage("");
        $manager->persist($admin);
        $manager->flush();
        
        $jk = new User();
        $jk->setEmail("toto@gmail.com")
        ->setPassword($this->encoder->encodePassword($jk, 'toto'))
        ->setRoles(['ROLE_USER'])
        ->setName("Jean-Kevin")
        ->setCreatedAt(new DateTime("now"))
        ->setLanguageNative($french)
        ->setLanguageLearn($english)
        ->setImage("https://www.tezenis.com/on/demandware.static/-/Library-Sites-TezenisContentLibrary/default/dw1947c472/images/Landing/TZN_20191009_LP_Snoopy/all_LP_Area07_Character01_CW4019_Snoopy_DiscoverCollection_tzn.png
        ");
        $manager->persist($jk);
        $manager->flush();

        $Pepita = new User();
        $Pepita->setEmail("titi@gmail.com")
        ->setPassword($this->encoder->encodePassword($Pepita, 'toto'))
        ->setRoles(['ROLE_USER'])
        ->setName("Pepita")
        ->setCreatedAt(new DateTime("now"))
        ->setLanguageNative($french)
        ->setLanguageLearn($english)
        ->setImage("https://www.tezenis.com/on/demandware.static/-/Library-Sites-TezenisContentLibrary/default/dw1947c472/images/Landing/TZN_20191009_LP_Snoopy/all_LP_Area07_Character01_CW4019_Snoopy_DiscoverCollection_tzn.png
        ");
        $manager->persist($Pepita);
        $manager->flush();

        $Lulu91 = new User();
        $Lulu91->setEmail("lulu@gmail.com")
        ->setPassword($this->encoder->encodePassword($Lulu91, 'toto'))
        ->setRoles(['ROLE_USER'])
        ->setName("Lulu91")
        ->setCreatedAt(new DateTime("now"))
        ->setLanguageNative($french)
        ->setLanguageLearn($finnois)
        ->setImage("https://www.tezenis.com/on/demandware.static/-/Library-Sites-TezenisContentLibrary/default/dw1947c472/images/Landing/TZN_20191009_LP_Snoopy/all_LP_Area07_Character01_CW4019_Snoopy_DiscoverCollection_tzn.png
        ");
        $manager->persist($Lulu91);
        $manager->flush();

        $Kiki = new User();
        $Kiki->setEmail("lala@gmail.com")
        ->setPassword($this->encoder->encodePassword($Kiki, 'toto'))
        ->setRoles(['ROLE_USER'])
        ->setName("Kiki")
        ->setCreatedAt(new DateTime("now"))
        ->setLanguageNative($french)
        ->setLanguageLearn($finnois)
        ->setImage("https://www.tezenis.com/on/demandware.static/-/Library-Sites-TezenisContentLibrary/default/dw1947c472/images/Landing/TZN_20191009_LP_Snoopy/all_LP_Area07_Character01_CW4019_Snoopy_DiscoverCollection_tzn.png
        ");
        $manager->persist($Kiki);
        $manager->flush();

        //CARDS
        for($i=0; $i<=20; $i++){
            $card = new Card();
            $card->setQuestion("rabbit")->setAnswer("lapin")->setAuthor($Kiki)->setCategory($vocabulaire);
            $manager->persist($card);
            $manager->flush();
            }

        //DECKS
        $deck1 = new Deck();
        $deck1->setName("Deck1")->setDescription("Une description du deck 1")->setPublic("true")->setAuthor($Lulu91)->setLangagueLearn($english);
        $manager->persist($deck1);
        $manager->flush();

        $deck2 = new Deck();
        $deck2->setName("Deck2")->setDescription("Une description du deck 2")->setPublic("false")->setAuthor($Lulu91)->setLangagueLearn($english);
        $manager->persist($deck2);
        $manager->flush();

    }
}
