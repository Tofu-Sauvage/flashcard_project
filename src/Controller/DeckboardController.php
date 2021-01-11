<?php

namespace App\Controller;

use App\Repository\CardRepository;
use App\Repository\DeckRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use DateTime;

class DeckboardController extends AbstractController {

  public function indexAction(CardRepository $cardRepository, DeckRepository $deckRepository) {
    $idActiveUser = $this->getUser()->getId();
    $lastCard = $cardRepository->findOneBy(['author' => $idActiveUser], ['id' => 'desc']);
    //  if ($lastCard->getCreatedAt() == null) {
    //    $lastCard->setCreatedAt(new DateTime("now"));
    //  }
    $lastDeck = $deckRepository->findOneBy(['author' => $idActiveUser], ['id' => 'desc']);
    $listDeck = $deckRepository->findBy(['author' => $idActiveUser]);

    // LA LIGNE CI DESSOUS EST LA A DES FINS DE TESTS SI VOUS VOULEZ LA TESTER, en attendant que la feature "ajouter aux favoris" soit faite ! 
    $this->getUser()->addFavorite($deckRepository->findAll()[0]);

    $listeFavouritedDecks = $this->getUser()->getFavorites();
    
    $nombreDeDecksFavoris = Count($listeFavouritedDecks);

    return $this->render("pages/user/deckboard.html.twig", ["last_card" => $lastCard, "last_deck" => $lastDeck, "list_deck" => $listDeck, "has_favs" => $nombreDeDecksFavoris, "list_deck_favoris" => $listeFavouritedDecks]);
  }
  public function rechercherVide(DeckRepository $deckRepository)  {
    $allMesDecks = $deckRepository->findBy(['public' => true]);

    $allDecks = array();
    for($i = 0; $i < count($allMesDecks) ; $i++)
    {
      if($allMesDecks[$i]->GetAuthor()->GetName() != $this->GetUser()->GetName())
      {
        array_push($allDecks, $allMesDecks[$i]);
      }
    }

    // LA LIGNE CI DESSOUS EST LA A DES FINS DE TESTS SI VOUS VOULEZ LA TESTER, en attendant que la feature "ajouter aux favoris" soit faite ! 
    $this->getUser()->addFavorite($deckRepository->findAll()[0]);

    $allFavsDecks = $this->getUser()->getFavorites();

    return $this->render("pages/user/recherche.html.twig", ["deck_all" => $allDecks, "favs_deck_all" => $allFavsDecks, "jeCherche" => '']);
  }

  public function rechercher(DeckRepository $deckRepository, $jeCherche)  {
    $allMesDecks = $deckRepository->findBy(['public' => true]);

    $jeCherche = (String)$jeCherche;
    $allDecks = array();
    if($jeCherche != '')
    {
      for($i = 0; $i < count($allMesDecks) ; $i++)
      {
        if(str_contains((String)($allMesDecks[$i]->GetName()), $jeCherche) || str_contains($allMesDecks[$i]->GetDescription(), $jeCherche) || str_contains($allMesDecks[$i]->GetAuthor()->GetName(), $jeCherche))
        {
          array_push($allDecks, $allMesDecks[$i]);
        }
      }
    }
    else
    {
      for($i = 0; $i < count($allMesDecks) ; $i++)
      {
        if($allDecks[$i]->GetAuthor()->GetName() != $this->GetUser()->GetName())
        {
          array_push($allDecks, $allMesDecks[$i]);
        }
      }
    }
    // LA LIGNE CI DESSOUS EST LA A DES FINS DE TESTS SI VOUS VOULEZ LA TESTER, en attendant que la feature "ajouter aux favoris" soit faite ! 
    $this->getUser()->addFavorite($deckRepository->findAll()[0]);

    $allFavsDecks = $this->getUser()->getFavorites();

    return $this->render("pages/user/recherche.html.twig", ["deck_all" => $allDecks, "favs_deck_all" => $allFavsDecks, "jeCherche" => $jeCherche]);
  }
}