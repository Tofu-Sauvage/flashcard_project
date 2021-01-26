<?php

namespace App\Controller;

use DateTime;
use App\Form\CardType;
use App\Repository\CardRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CardsController extends AbstractController {

  /* Vue Admin : affiche l'intégralité des cartes créées */
  public function indexAction(CardRepository $cardRepository, Request $request, PaginatorInterface $paginator) {
    $cardsTable = $cardRepository->findCardsDesc();

    $limit = 10; 
    $firstPage = 1;

    $cards = $paginator->paginate(
        $cardsTable,
        $request->query->getInt('page', $firstPage),
        $limit
    );
    
    return $this->render('./pages/administration/cards.html.twig', ['cards'=>$cards, 'cardsTable'=>$cardsTable]);
  }

  /* Vue User : affiche l'intégralité des cartes créées */
  public function indexCardGestionAction(CategoryRepository $categoryRepository, CardRepository $cardRepository, Request $request, PaginatorInterface $paginator) {
    $idActiveUser = $this->getUser()->getID();
    $listeCategories = $categoryRepository->findBy([], ['id' => 'desc']);
    $listeCards = $cardRepository->findBy(['author' => $idActiveUser]);

    $limit = 10; 
    $firstPage = 1;

    $paginationCards = $paginator->paginate(
        $listeCards,
        $request->query->getInt('page', $firstPage),
        $limit
    );

    return $this->render('./pages/user/cardGestion.html.twig', ['categories' => $listeCategories, 'cards' => $listeCards, 'paginationCards'=>$paginationCards]);
  }

  /* Vue User : affiche le formulaire de création de cartes */
  public function cardCreateAction(Request $request, EntityManagerInterface $em, $categoryId, CategoryRepository $categoryRepository)
  {
    $categorySelected = $categoryRepository->findOneBy(['id' => $categoryId]);
    $modeEdition = false;
    
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

      $card->setCreatedAt(new DateTime("now"))
           ->setCategory($categorySelected)
           ->setAuthor($this->getUser());

      $em->persist($card);
      $em->flush();

      $this->addFlash('success', "La carte a bien été ajoutée");
      return $this->redirectToRoute('card-gestion');
    }

    return $this->render('./pages/user/cardForm.html.twig', ['cardForm' => $form->createView(), 'category' => $categorySelected, 'modeEdition' => $modeEdition]);
  }

  /* Vue User : affiche le formulaire de modification de cartes */
  public function cardUpdateAction(Request $request, EntityManagerInterface $em, CardRepository $cardRepository, CategoryRepository $categoryRepository, $categoryId, $cardId) {
    
    $categorySelected = $categoryRepository->findOneBy(['id' => $categoryId]);
    $modeEdition = true;
    
    $card = $cardRepository->findOneBy(['id' => $cardId]);
    $form = $this->createForm(CardType::class, $card);

    $form->handleRequest($request);

    if($form->isSubmitted()){
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

      $this->addFlash('success', "La carte a bien été modifié");
      return $this->redirectToRoute('card-gestion');
    }

    return $this->render('./pages/user/cardForm.html.twig', ['cardForm' => $form->createView(), 'category' => $categorySelected, 'modeEdition' => $modeEdition]);
  }

  /* Vue Admin : affiche le détail d'une carte */
  public function detailAction(CardRepository $cardRepository, $id)
  {
    $card =  $cardRepository->findOneBy(['id' => $id]);
    return $this->render('./pages/administration/card.html.twig', ['card' => $card]);
  }

  /* Vue User : affiche le détail d'une carte */
  public function detailCardUserAction(CardRepository $cardRepository, $id)
  {
    $card =  $cardRepository->findOneBy(['id' => $id]);
    return $this->render('./pages/user/cardDetail.html.twig', ['card' => $card]);
  }

  /* Vue Admin : suppression d'une carte */
  public function deleteAction(EntityManagerInterface $em, CardRepository $cardRepository, $id)
  {
    $card = $cardRepository->find($id);
    $em->remove($card);
    $em->flush();
    $this->addFlash('success', 'La carte a bien été supprimée !');
    return $this->redirectToRoute('admin-cards');
  }

  /* Vue User : suppression d'une carte */
  public function deleteCardUserAction(EntityManagerInterface $em, CardRepository $cardRepository, $id)
  {
    $card = $cardRepository->find($id);
    $em->remove($card);
    $em->flush();
    $this->addFlash('success', 'La carte a bien été supprimée !');
    return $this->redirectToRoute('card-gestion');
  }
}