<?php

namespace App\Controller;

use App\Form\DeckType;
use App\Repository\CardRepository;
use App\Repository\DeckRepository;
use DateTime;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DecksController extends AbstractController {

  public function indexAction(DeckRepository $deckRepository) {
    $decks = $deckRepository->findAll();
    return $this->render('./pages/administration/decks.html.twig', ['decks'=>$decks]);
  }

  public function deckCreateAction(Request $request, EntityManagerInterface $em)
  {
    $form = $this->createForm(DeckType::class);
    $form->handleRequest($request);
    if ($form->isSubmitted()) {
      $deck = $form->getData();
      
      $em->persist($deck);
      $em->flush();
    }

    return $this->render('./pages/administration/deckForm.html.twig', ['deckForm' => $form->createView()]);
  }

  public function indexGestionAction(DeckRepository $deckRepository) {
    $idActiveUser = $this->getUser()->getID();
    $listeDecks = $deckRepository->findBy(['author' => $idActiveUser]);
    return $this->render('./pages/user/deckGestion.html.twig', ['decks' => $listeDecks]);
  }

  public function deckUserCreateAction(Request $request, EntityManagerInterface $em)
  {
    $form = $this->createForm(DeckType::class);
    $form->handleRequest($request);
    if ($form->isSubmitted()) {
      $deck = $form->getData();
      
      $deck->setAuthor($this->getUser())
           ->setCreatedAt(new DateTime("now"));

      $em->persist($deck);
      $em->flush();

      $this->addFlash('success', "Le deck a bien été crée");
      return $this->redirectToRoute('deck-gestion');
    }

    return $this->render('./pages/user/deckForm.html.twig', ['deckForm' => $form->createView()]);
  }

  public function detailAction(DeckRepository $deckRepository, $id)
  {
    $deck =  $deckRepository->findOneBy(['id' => $id]);     
    return $this->render('./pages/administration/deck.html.twig', ['deck' => $deck]);
  }

  public function detailUserAction(DeckRepository $deckRepository, CardRepository $cardRepository, $id)
  {
    $deck =  $deckRepository->findOneBy(['id' => $id]);

    dd($deck);

    $idActiveUser = $this->getUser()->getID();
    $listeCards = $cardRepository->findBy(['author' => $idActiveUser, !'deck_id' => $id]);
    // $criteria = Criteria::create();
    // $criteria->where(Criteria::expr()->neq('id', $id));
    
    // $listeCards->srcFiles->matching($criteria);
    // dd($listeCards);

    return $this->render('./pages/user/deckDetail.html.twig', ['deck' => $deck, "cards" => $listeCards]);
  }

  public function deleteAction(EntityManagerInterface $em, DeckRepository $deckRepository, $id)
  {
    $deck = $deckRepository->find($id);
    $em->remove($deck);
    $em->flush();
    $this->addFlash('success-deck', 'Le deck a bien été supprimé !');
    return $this->redirectToRoute('admin-decks');
  }

  public function addCardToDeckFromDeckDetailAction(DeckRepository $deckRepository, CardRepository $cardRepository, EntityManagerInterface $em, $idCard, $idDeck) {
    $deck = $deckRepository->findOneBy(["id" => $idDeck]);
    $card = $cardRepository->findOneBy(["id" => $idCard]);
    $deck->addCard($card);

    $em->persist($deck);
    $em->flush();

    $this->addFlash('success', 'La carte a bien été ajouté !');
    return $this->redirectToRoute('deck-detail', ["id" => $idDeck]);
  }

  public function removeCardToDeckFromDeckDetailAction(DeckRepository $deckRepository, CardRepository $cardRepository, EntityManagerInterface $em, $idCard, $idDeck) {
    $deck = $deckRepository->findOneBy(["id" => $idDeck]);
    $card = $cardRepository->findOneBy(["id" => $idCard]);
    $deck->removeCard($card);

    $em->persist($deck);
    $em->flush();

    $this->addFlash('success', 'La carte a bien été supprimé du deck !');
    return $this->redirectToRoute('deck-detail', ["id" => $idDeck]);
  }

  public function shuffleLesCartes($deckRepository, $deckId)
  {
    $deck = $deckRepository->findOneBy(['id' => $deckId]);

    $cards = $deck->getCards();

    $arrayCards = array();
    for ($i = 0 ; $i < count($cards) ; $i++)
    {
      array_push($arrayCards, $cards[$i]);
    }
    shuffle($arrayCards);

    return $arrayCards;
  }

  public function launchRevisionAction(DeckRepository $deckRepository, $deckId)
  {
    $mesCartes = $this->shuffleLesCartes($deckRepository, $deckId);
    return $this->render('./pages/user/revision.html.twig', ['cartes' => $mesCartes]);
  }

  public function launchQuizAction(DeckRepository $deckRepository, $deckId)
  {
    $mesCartes = $this->shuffleLesCartes($deckRepository, $deckId);
    return $this->render('./pages/user/quiz.html.twig', ['cartes' => $mesCartes]);
  }
}