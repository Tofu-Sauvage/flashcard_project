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
    //  if ($lastCard->getCreatedAt() ==  null) {
    //    $lastCard->setCreatedAt(new DateTime("now"));
    //  }
    $lastDeck = $deckRepository->findOneBy(['author' => $idActiveUser], ['id' => 'desc']);

    return $this->render("pages/user/deckboard.html.twig", ["last_card" => $lastCard, "last_deck" => $lastDeck]);
  }
}