<?php

namespace App\Controller;

use App\Repository\CardRepository;
use App\Repository\DeckRepository;
use App\Repository\LanguageRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
  public function indexAction(UserRepository $userRepository, CardRepository $cardRepository, DeckRepository $deckRepository, LanguageRepository $languageRepository)
  {
    $lastUsers = $userRepository->findLastestUsers();
    $lastCards = $cardRepository->findLatestCards();
    $lastDecks = $deckRepository->findLastestDecks();
    $allUsers =  $userRepository->findAll();
    $allCards = $cardRepository->findAll();
    $allDecks = $deckRepository->findAll();
    return $this->render('./pages/administration/dashboard.html.twig', ['users'=>$lastUsers,'cards'=>$lastCards, 'decks'=> $lastDecks, 'allUsers'=>$allUsers, 'allCards'=>$allCards, 'allDecks'=>$allDecks]);
  }
}
