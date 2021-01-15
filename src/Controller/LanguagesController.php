<?php

namespace App\Controller;

use App\Form\LanguageType;
use App\Repository\CardRepository;
use App\Repository\DeckRepository;
use App\Repository\UserRepository;
use App\Repository\LanguageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LanguagesController extends AbstractController {

  public function indexAction(LanguageRepository $languageRepository, Request $request, PaginatorInterface $paginator) {
    $languagesTable = $languageRepository->findAll();

    $limit = 10; 
    $firstPage = 1;

    $languages = $paginator->paginate(
        $languagesTable, // Requête contenant les données à paginer (ici nos articles)
        $request->query->getInt('page', $firstPage), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
        $limit // Nombre de résultats par page
    );
    return $this->render('./pages/administration/languages.html.twig', ['languages'=>$languages, 'languagesTable'=>$languagesTable]);
  }

  public function languageCreateAction(Request $request, EntityManagerInterface $em)
  {
    $form = $this->createForm(LanguageType::class);
    $form->handleRequest($request);
    if ($form->isSubmitted()) {
      $language = $form->getData();
      $file = $form->get('flag')->getData();
      if ($file) {
        $original = pathInfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $uniqueName = $original . "-" . uniqid() . "." . $file->guessExtension();
        $file->move(
          $this->getParameter('uploads'),
          $uniqueName
        );
        $language->setFlag($uniqueName);
      }

      $em->persist($language);
      $em->flush();
      //$this->addFlash('success-category', 'La catégorie a bien été ajoutée !');
      return $this->redirectToRoute('admin-languages');
    }
    return $this->render('./pages/administration/languageForm.html.twig', ['languageForm' => $form->createView()]);
  }

  public function languageEditAction(Request $request, LanguageRepository $languageRepository, EntityManagerInterface $em, $id)
  {

    $language = $languageRepository->find($id);
    $languageForm = $this->createForm(LanguageType::class, $language);
    $languageForm->handleRequest($request);

    if ($languageForm->isSubmitted()) {
      $language = $languageForm->getData();
      $file = $languageForm->get('flag')->getData();
      if ($file) {
        $original = pathInfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $uniqueName = $original . "-" . uniqid() . "." . $file->guessExtension();
        $file->move(
          $this->getParameter('uploads'),
          $uniqueName
        );
        $language->setFlag($uniqueName);
      }
      $em->persist($language);
      $em->flush();
      $this->addFlash('success-edit-language', 'Le langage a bien été modifié !');
      return $this->redirectToRoute('admin-languages');
        }
    return $this->render('./pages/administration/languageForm.html.twig', ['languageForm' => $languageForm->createView()]);
  }

  public function languageDeleteAction(EntityManagerInterface $em, LanguageRepository $languageRepository, $id)
  {
    $language = $languageRepository->find($id);
    $em->remove($language);
    $em->flush();
    $this->addFlash('success-language', 'Le langage a bien été supprimé !');
    return $this->redirectToRoute('admin-languages');  }

    public function detailAction(LanguageRepository $languageRepository, $id, UserRepository $userRepository, CardRepository $cardRepository, DeckRepository $deckRepository, Request $request, PaginatorInterface $paginator)
    {
      $language =  $languageRepository->findOneBy(['id' => $id]);
      $users = $userRepository->findAll();
      $allCards = $cardRepository->findAll();
      $decks = $deckRepository->findAll();

      $limit = 10; 
      $firstPage = 1;
  
      $cards = $paginator->paginate(
          $allCards, // Requête contenant les données à paginer (ici nos articles)
          $request->query->getInt('page', $firstPage), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
          $limit // Nombre de résultats par page
      );

      return $this->render('./pages/administration/language.html.twig', ['language' => $language, 'users'=>$users, 'cards'=>$cards, 'decks'=>$decks, 'allCards'=>$allCards]);
    }
}