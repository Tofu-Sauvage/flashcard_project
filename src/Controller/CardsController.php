<?php

namespace App\Controller;

use App\Form\CardType;
use App\Repository\CardRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CardsController extends AbstractController {

  public function indexAction(CardRepository $cardRepository) {
    $cards = $cardRepository->findAll();
    return $this->render('./pages/administration/card.html.twig', ['cards'=>$cards]);
  }

  public function cardCreateAction(Request $request, EntityManagerInterface $em)
  {
    $form = $this->createForm(CardType::class);
    $form->handleRequest($request);
    if ($form->isSubmitted()) {
      $card = $form->getData();
      $file = $form->get('image')->getData();
      if ($file) {
        $original = pathInfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $uniqueName = $original . "-" . uniqid() . "." . $file->guessExtension();
        $file->move(
          $this->getParameter('uploads'),
          $uniqueName
        );
        $card->setImage($uniqueName);
      }
      $em->persist($card);
      $em->flush();
    }


    return $this->render('./pages/user/cardForm.html.twig', ['cardForm' => $form->createView()]);

  }

  public function detailAction(CardRepository $cardRepository, $id)
  {
    $card =  $cardRepository->findOneBy(['id' => $id]);
    return $this->render('./pages/administration/card.html.twig', ['card' => $card]);
  }
}