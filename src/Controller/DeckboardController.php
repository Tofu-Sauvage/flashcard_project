<?php

namespace App\Controller;

use App\Repository\CardRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DeckboardController extends AbstractController {

  public function indexAction(CardRepository $cardRepository) {
    $lastCards = $cardRepository->findOneBy([], ['id' => 'desc']);
    // dd($lastCards);
    $lastCards = null;
    return $this->render("pages/user/deckboard.html.twig", ["cards" => $lastCards]);
  }
}