<?php

namespace App\Controller;

use App\Repository\CardRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CardsController extends AbstractController {

  public function indexAction(CardRepository $cardRepository) {
    $cards = $cardRepository->findAll();
    return $this->render('./pages/administration/cards.html.twig', ['cards'=>$cards]);
  }

  public function detailAction(CardRepository $cardRepository, $id)
  {
    $card =  $cardRepository->findOneBy(['id' => $id]);
    return $this->render('./pages/administration/card.html.twig', ['card' => $card]);
  }
}