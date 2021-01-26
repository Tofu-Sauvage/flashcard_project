<?php

namespace App\Controller;

use App\Repository\CardRepository;
use App\Repository\DeckRepository;
use App\Repository\MessageRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
  /* Affiche les dernières inofs dans le dashboard Admin */
  public function indexAction(UserRepository $userRepository, CardRepository $cardRepository, DeckRepository $deckRepository, MessageRepository $messageRepository)
  {
    $lastUsers = $userRepository->findLastestUsers();
    $lastCards = $cardRepository->findLatestCards();
    $lastDecks = $deckRepository->findLastestDecks();
    $lastMessages = $messageRepository->findLatestMessages();
    $allUsers =  $userRepository->findAll();
    $allCards = $cardRepository->findAll();
    $allDecks = $deckRepository->findAll();
    $allMessages = $messageRepository->findAll();
    return $this->render('./pages/administration/dashboard.html.twig', ['users'=>$lastUsers,'cards'=>$lastCards, 'decks'=> $lastDecks, 'messages'=> $lastMessages, 'allUsers'=>$allUsers, 'allCards'=>$allCards, 'allDecks'=>$allDecks, 'allMessages'=>$allMessages]);
  }
}
