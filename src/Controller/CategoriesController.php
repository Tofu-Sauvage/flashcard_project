<?php

namespace App\Controller;

use App\Form\CategoryType;
use App\Repository\CardRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoriesController extends AbstractController {

  /* Vue Admin : affiche toutes les catégories */
  public function indexAction(CategoryRepository $categoryRepository) {
    $categories = $categoryRepository->findAll();
    return $this->render('./pages/administration/categories.html.twig', ['categories'=>$categories]);
  }

  /* Vue Admin : affiche le formulaire de création de catégorie */
  public function categoryCreateAction(Request $request, EntityManagerInterface $em)
  {
    $form = $this->createForm(CategoryType::class);
    $form->handleRequest($request);
    if ($form->isSubmitted()) {
      $category = $form->getData();

      $em->persist($category);
      $em->flush();
      $this->addFlash('success-create-category', 'La catégorie a bien été ajoutée !');
      return $this->redirectToRoute('admin-categories');
    }
    return $this->render('./pages/administration/categoryForm.html.twig', ['categoryForm' => $form->createView()]);
  }

  /* Vue Admin : affiche le formulaire de modification de catégorie */
  public function categoryEditAction(Request $request, CategoryRepository $categoryRepository, EntityManagerInterface $em, $id)
  {
    $category = $categoryRepository->find($id);
    $categoryForm = $this->createForm(CategoryType::class, $category);
    $categoryForm->handleRequest($request);

    if ($categoryForm->isSubmitted()) {
      $category = $categoryForm->getData();
      $em->persist($category);
      $em->flush();
      $this->addFlash('success-edit-category', 'La catégorie a bien été modifiée !');
      return $this->redirectToRoute('admin-categories');
        }
    return $this->render('./pages/administration/categoryForm.html.twig', ['categoryForm' => $categoryForm->createView()]);
  }

  /* Vue Admin : affiche le formulaire de suppression de catégorie */
  public function categoryDeleteAction(EntityManagerInterface $em, CategoryRepository $categoryRepository, $id)
  {
    $category = $categoryRepository->find($id);
    $em->remove($category);
    $em->flush();
    $this->addFlash('success-category', 'La catégorie a bien été supprimée !');
    return $this->redirectToRoute('admin-categories');  
  }

  /* Vue Admin : affiche le détail d'une catégorie avec les cartes s'y rapportant */
  public function detailAction(CategoryRepository $categoryRepository, $id, CardRepository $cardRepository)
  {
    $category =  $categoryRepository->findOneBy(['id' => $id]);
    $cards = $cardRepository->findAll();
    return $this->render('./pages/administration/category.html.twig', ['category' => $category, 'cards'=>$cards]);
  }

}