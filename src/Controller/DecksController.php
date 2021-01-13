<?php

namespace App\Controller;

use DateTime;
use App\Entity\Card;
use App\Form\DeckType;
use Doctrine\ORM\EntityManager;
use App\Repository\CardRepository;
use App\Repository\DeckRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Collections\Criteria;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DecksController extends AbstractController {

  public function indexAction(DeckRepository $deckRepository, Request $request, PaginatorInterface $paginator) {
    $decksTable = $deckRepository->findAll();

    $limit = 10; 
    $firstPage = 1;

    $decks = $paginator->paginate(
        $decksTable, // Requête contenant les données à paginer (ici nos articles)
        $request->query->getInt('page', $firstPage), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
        $limit // Nombre de résultats par page
    );
    return $this->render('./pages/administration/decks.html.twig', ['decks'=>$decks, 'decksTable'=>$decksTable]);
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

  public function indexGestionAction(DeckRepository $deckRepository, Request $request, PaginatorInterface $paginator) {
    $idActiveUser = $this->getUser()->getID();
    $listeDecks = $deckRepository->findBy(['author' => $idActiveUser]);

    $limit = 5; 
    $firstPage = 1;

    $paginationDecks = $paginator->paginate(
        $listeDecks, // Requête contenant les données à paginer (ici nos articles)
        $request->query->getInt('page', $firstPage), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
        $limit // Nombre de résultats par page
    );

    return $this->render('./pages/user/deckGestion.html.twig', ['decks' => $listeDecks, 'paginationDecks'=>$paginationDecks]);
  }

  public function deckUserCreateAction(Request $request, EntityManagerInterface $em)
  {
    $modeEdition = false;

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

    return $this->render('./pages/user/deckForm.html.twig', ['deckForm' => $form->createView(), 'modeEdition' => $modeEdition]);
  }

  public function deckUserUpdateAction(Request $request, DeckRepository $deckRepository, EntityManagerInterface $em, $id)
  {
    $modeEdition = true;

    $deck = $deckRepository->findOneBy(['id' => $id]);
    $form = $this->createForm(DeckType::class, $deck);
    $form->handleRequest($request);
    if ($form->isSubmitted()) {
      $deck = $form->getData();
      
      $deck->setAuthor($this->getUser())
           ->setCreatedAt(new DateTime("now"));

      $em->persist($deck);
      $em->flush();

      $this->addFlash('success', "Le deck a bien été modifié");
      return $this->redirectToRoute('deck-detail', ['id' => $id]);
    }

    return $this->render('./pages/user/deckForm.html.twig', ['deckForm' => $form->createView(), 'modeEdition' => $modeEdition]);
  }

  public function deckFavAction(DeckRepository $deckRepository, EntityManagerInterface $em, $deckId)
  {
    $deck = $deckRepository->findOneBy(['id' => $deckId]);
    $esTuDejaDansLesFavs = false;

    $decksFavoris = $this->getUser()->getFavorites();
    for ($i = 0 ; $i < count($decksFavoris) ; $i++)
    {
      if($decksFavoris[$i]->getId() == $deckId)
        $esTuDejaDansLesFavs = true;
    }

    if(!$esTuDejaDansLesFavs)
    {
      $this->getUser()->addFavorite($deck);
      $em->persist($deck);
      $em->flush();
      $this->addFlash('success', "Le deck a été ajouté dans vos favoris !");
    }
    else
    {
      $this->addFlash('info', "Ce deck est déjà dans vos favoris.");
    }
    return($this->rechercherDeck($deckRepository, $deck));
  }

  public function deckSignalAction(DeckRepository $deckRepository, $deckId)
  {
    $deck = $deckRepository->findOneBy(['id' => $deckId]);

    // le signalement aux administrateurs se ferait ici.

    $this->addFlash('info', "Votre demande a bien été transmise.");

    return($this->signalerDeck($deckRepository, $deck));
  }

  public function detailAction(DeckRepository $deckRepository, $id, UserRepository $userRepository)
  {
    $deck =  $deckRepository->findOneBy(['id' => $id]);     
    $users = $userRepository->findAll();

    $tags = $deck->getTags();
    $tagsTable = explode(" ", $tags);

    return $this->render('./pages/administration/deck.html.twig', ['deck' => $deck, 'users'=>$users, 'tagsTable'=>$tagsTable]);
  }

  public function detailUserAction(DeckRepository $deckRepository, CardRepository $cardRepository, $id, Request $request, PaginatorInterface $paginator)
  {
    $deck =  $deckRepository->findOneBy(['id' => $id]);

    $activeUser = $this->getUser()->getId();
    $listeAllCards = $cardRepository->findBy(['author' => $activeUser]);
    $listeCards = [];
    
    //  Tri entre les cartes de l'utilisateurs et celles déja associé au deck. Renvoie les cartes non-associés.
    foreach($listeAllCards as $card) {
      $ajouterCard = true;

      foreach ($deck->getCards() as $cardDeck) {
        if ($card->getId() == $cardDeck->getId()) 
          $ajouterCard = false;
      }

      if($ajouterCard) 
        array_push($listeCards, $card); 
    }
    
    $limit = 10; 
    $firstPage = 1;

    $paginationCards = $paginator->paginate(
        $listeCards, // Requête contenant les données à paginer (ici nos articles)
        $request->query->getInt('page', $firstPage), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
        $limit // Nombre de résultats par page
    );

    return $this->render('./pages/user/deckDetail.html.twig', ['deck' => $deck, "cards" => $listeCards, 'paginationCards'=>$paginationCards]);
  }

  public function deleteAction(EntityManagerInterface $em, DeckRepository $deckRepository, $id)
  {
    $deck = $deckRepository->find($id);
    $em->remove($deck);
    $em->flush();
    $this->addFlash('success-deck', 'Le deck a bien été supprimé !');
    return $this->redirectToRoute('admin-decks');
  }

  public function deleteUserAction(EntityManagerInterface $em, DeckRepository $deckRepository, $id)
  {
    $deck = $deckRepository->find($id);
    $em->remove($deck);
    $em->flush();
    $this->addFlash('success', 'Le deck a bien été supprimé !');
    return $this->redirectToRoute('deck-gestion');
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


  public function rechercherVide(DeckRepository $deckRepository)  {
    $allMesDecks = $deckRepository->findBy(['public' => true]);

    $allDecks = array();
    for($i = 0; $i < count($allMesDecks) ; $i++)
    {
      if($allMesDecks[$i]->getAuthor()->getName() != $this->getUser()->getName())
      {
        array_push($allDecks, $allMesDecks[$i]);
      }
    }

    $allFavsDecks = $this->getUser()->getFavorites();

    return $this->render("pages/user/recherche.html.twig", ["deck_all" => $allDecks, "favs_deck_all" => $allFavsDecks, "jeCherche" => '']);
  }

  public function rechercher(DeckRepository $deckRepository, $jeCherche)  {
    $allMesDecks = $deckRepository->findBy(['public' => true]);

    $jeCherche = (String)$jeCherche;
    $allDecks = array();

    for($i = 0; $i < count($allMesDecks) ; $i++)
    {
      if(str_contains((String)($allMesDecks[$i]->getName()), $jeCherche) || str_contains($allMesDecks[$i]->getDescription(), $jeCherche) || str_contains($allMesDecks[$i]->getAuthor()->getName(), $jeCherche))
      {
        array_push($allDecks, $allMesDecks[$i]);
      }
    }

    $allFavsDecks = $this->getUser()->getFavorites();

    return $this->render("pages/user/recherche.html.twig", ["deck_all" => $allDecks, "favs_deck_all" => $allFavsDecks, "jeCherche" => $jeCherche]);
  }

  public function rechercherDeck(DeckRepository $deckRepository, $deckId){
    $monDeck = $deckRepository->findOneBy(['id' => $deckId]);
    $mesCartes = $monDeck->getCards();

    return $this->render("pages/user/rechercheDeck.html.twig", ["deck" => $monDeck, "cards" => $mesCartes]);
  }

  public function signalerDeck(DeckRepository $deckRepository, $deckId){
    $monDeck = $deckRepository->findOneBy(['id' => $deckId]);

    return $this->render("pages/user/signalerDeck.html.twig", ["deck" => $monDeck]);
  }
}