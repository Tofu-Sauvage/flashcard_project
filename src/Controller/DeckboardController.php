<?php

namespace App\Controller;

use App\Repository\CardRepository;
use App\Repository\DeckRepository;
use App\Repository\LanguageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use DateTime;

class DeckboardController extends AbstractController {

  public function indexAction(CardRepository $cardRepository, DeckRepository $deckRepository, LanguageRepository $languageRepository) {
    $idActiveUser = $this->getUser()->getId();
    $lastCard = $cardRepository->findOneBy(['author' => $idActiveUser], ['id' => 'desc']);
    
    $lastDeck = $deckRepository->findOneBy(['author' => $idActiveUser], ['id' => 'desc']);
    $listDeck = $deckRepository->findBy(['author' => $idActiveUser]);

    $languageLearn = $languageRepository->findOneBy(['id' => $this->getUser()->getLanguageLearn()]);

    $listeFavouritedDecks = $this->getUser()->getFavorites();
    
    $nombreDeDecksFavoris = count($listeFavouritedDecks);

    return $this->render("pages/user/deckboard.html.twig", [
      "last_card" => $lastCard, 
      "last_deck" => $lastDeck, 
      "list_deck" => $listDeck, 
      "has_favs" => $nombreDeDecksFavoris, 
      "list_deck_favoris" => $listeFavouritedDecks, 
      "language_learn" => $languageLearn
      ]);
  }
}