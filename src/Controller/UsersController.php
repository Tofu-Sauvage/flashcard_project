<?php

namespace App\Controller;

use App\Repository\CardRepository;
use App\Repository\DeckRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UsersController extends AbstractController {

  /* Vue Admin : affiche la liste des inscrits */
  public function indexAction(UserRepository $userRepository, Request $request, PaginatorInterface $paginator) {
    $usersTable = $userRepository->findAll();

    $limit = 10; 
    $firstPage = 1;

    $users = $paginator->paginate(
        $usersTable, // Requête contenant les données à paginer (ici nos articles)
        $request->query->getInt('page', $firstPage), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
        $limit // Nombre de résultats par page
    );
    return $this->render('./pages/administration/users.html.twig', ['users'=>$users, 'usersTable'=>$usersTable]);
  }

  /* Vue Admin : affiche les détails d'un inscrit */
  public function detailAction(UserRepository $userRepository, $id, CardRepository $cardRepository, Request $request, PaginatorInterface $paginator, DeckRepository $deckRepository)
  {
    $user =  $userRepository->findOneBy(['id' => $id]);
    $usersCards = $cardRepository->findByUser($id);
    $usersDecks = $deckRepository->findByUser($id);

    $limit = 10; 
    $firstPage = 1;

    $cards = $paginator->paginate(
        $usersCards,
        $request->query->getInt('page', $firstPage),
        $limit
    );

    $decks = $paginator->paginate(
      $usersDecks,
      $request->query->getInt('page', $firstPage),
      $limit 
  );
    return $this->render('./pages/administration/user.html.twig', ['user' => $user, 'cards'=>$cards, 'usersCards'=>$usersCards, 'decks'=>$decks, 'usersDecks'=>$usersDecks]);
  }

  /* Admin : gère la suppression d'un inscrit */
  public function deleteAction(EntityManagerInterface $em, UserRepository $userRepository, $id)
  {
    $user = $userRepository->find($id);
    $em->remove($user);
    $em->flush();
    $this->addFlash('success-user', 'L\'utilisateur a bien été supprimé !');
    return $this->redirectToRoute('admin-users');
  }
}