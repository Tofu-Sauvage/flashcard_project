<?php

namespace App\Controller;

use App\Repository\CardRepository;
use App\Repository\DeckRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class DeckboardController extends AbstractController {

  public function indexAction(CardRepository $cardRepository, DeckRepository $deckRepository) {
    $idActiveUser = $this->getUser()->getId();
    $lastCard = $cardRepository->findOneBy(['author' => $idActiveUser], ['id' => 'desc']);
    //  if ($lastCard->getCreatedAt() ==  null) {
       
    //    $lastCard->setCreatedAt(new \DateTimeInterface('@'.strtotime('now')));
    //  }
    $lastDeck = $deckRepository->findOneBy(['author' => $idActiveUser], ['id' => 'desc']);
    $listDeck = $deckRepository->findBy(['author' => $idActiveUser]);

    // LA LIGNE CI DESSOUS EST LA A DES FINS DE TESTS SI VOUS VOULEZ LA TESTER, en attendant que la feature "ajouter aux favoris" soit faite ! 
    $this->getUser()->addFavorite($deckRepository->findAll()[0]);

    $listeFavouritedDecks = $this->getUser()->getFavorites();
    
    $nombreDeDecksFavoris = Count($listeFavouritedDecks);

    return $this->render("pages/user/deckboard.html.twig", ["last_card" => $lastCard, "last_deck" => $lastDeck, "list_deck" => $listDeck, "has_favs" => $nombreDeDecksFavoris, "list_deck_favoris" => $listeFavouritedDecks]);
  }
}