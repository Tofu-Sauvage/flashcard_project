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
    $users = $userRepository->findAll();
    $languages = $languageRepository->findAll();
    $cards = $cardRepository->findAll();
    $decks = $deckRepository->findAll();
    return $this->render('./pages/administration/dashboard.html.twig', ['users'=>$users, 'languages'=>$languages, 'cards'=>$cards, 'decks'=> $decks]);
  }
}
