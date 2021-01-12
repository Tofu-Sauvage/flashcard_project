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

  public function indexAction(CardRepository $cardRepository, Request $request, PaginatorInterface $paginator) {
    $cardsTable = $cardRepository->findAll();

    $limit = 10; 
    $firstPage = 1;

    $cards = $paginator->paginate(
        $cardsTable, // Requête contenant les données à paginer (ici nos articles)
        $request->query->getInt('page', $firstPage), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
        $limit // Nombre de résultats par page
    );
    
    return $this->render('./pages/administration/cards.html.twig', ['cards'=>$cards, 'cardsTable'=>$cardsTable]);
  }

  public function indexCardGestionAction(CategoryRepository $categoryRepository, CardRepository $cardRepository) {
    $idActiveUser = $this->getUser()->getID();
    $listeCategories = $categoryRepository->findBy([], ['id' => 'desc']);
    $listeCards = $cardRepository->findBy(['author' => $idActiveUser]);

    return $this->render('./pages/user/cardGestion.html.twig', ['categories' => $listeCategories, 'cards' => $listeCards]);
  }

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

  public function detailAction(CardRepository $cardRepository, $id)
  {
    $card =  $cardRepository->findOneBy(['id' => $id]);
    return $this->render('./pages/administration/card.html.twig', ['card' => $card]);
  }

  public function detailCardUserAction(CardRepository $cardRepository, $id)
  {
    $card =  $cardRepository->findOneBy(['id' => $id]);
    return $this->render('./pages/user/cardDetail.html.twig', ['card' => $card]);
  }

  public function deleteAction(EntityManagerInterface $em, CardRepository $cardRepository, $id)
  {
    $card = $cardRepository->find($id);
    $em->remove($card);
    $em->flush();
    $this->addFlash('success', 'La carte a bien été supprimée !');
    return $this->redirectToRoute('admin-cards');
  }

  public function deleteCardUserAction(EntityManagerInterface $em, CardRepository $cardRepository, $id)
  {
    $card = $cardRepository->find($id);
    $em->remove($card);
    $em->flush();
    $this->addFlash('success', 'La carte a bien été supprimée !');
    return $this->redirectToRoute('card-gestion');
  }
}