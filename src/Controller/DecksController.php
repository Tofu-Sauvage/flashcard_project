<?php

namespace App\Controller;

use App\Repository\DeckRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DecksController extends AbstractController {

  public function indexAction(DeckRepository $deckRepository) {
    $decks = $deckRepository->findAll();
    return $this->render('./pages/administration/decks.html.twig', ['decks'=>$decks]);
  }
}