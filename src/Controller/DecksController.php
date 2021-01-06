<?php

namespace App\Controller;

use App\Form\DeckType;
use App\Repository\DeckRepository;
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
  public function detailAction(DeckRepository $deckRepository, $id)
  {
    $deck =  $deckRepository->findOneBy(['id' => $id]);
    return $this->render('./pages/administration/deck.html.twig', ['deck' => $deck]);
  }

  public function deleteAction(EntityManagerInterface $em, DeckRepository $deckRepository, $id)
  {
    $deck = $deckRepository->find($id);
    $em->remove($deck);
    $em->flush();
    $this->addFlash('success-deck', 'Le deck a bien été supprimé !');
    return $this->redirectToRoute('admin-decks');
  }
}