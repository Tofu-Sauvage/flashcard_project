<?php

namespace App\Controller;

use App\Form\LanguageType;
use App\Repository\LanguageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LanguagesController extends AbstractController {

  public function indexAction(LanguageRepository $languageRepository) {
    $languages = $languageRepository->findAll();
    return $this->render('./pages/administration/languages.html.twig', ['languages'=>$languages]);
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
      //$this->addFlash('success-category', 'La catégorie a bien été modifiée !');
      return $this->redirectToRoute('admin-languages');
        }
    return $this->render('./pages/administration/languageForm.html.twig', ['languageForm' => $languageForm->createView()]);
  }


  public function languageDeleteAction(EntityManagerInterface $em, LanguageRepository $languageRepository, $id)
  {
    $language = $languageRepository->find($id);
    $em->remove($language);
    $em->flush();
    //$this->addFlash('success-category', 'La catégorie a bien été supprimée !');
    return $this->redirectToRoute('admin-languages');  }
}