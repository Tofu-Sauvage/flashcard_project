<?php

namespace App\Controller;

use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoriesController extends AbstractController {

  public function indexAction(CategoryRepository $categoryRepository) {
    $categories = $categoryRepository->findAll();
    return $this->render('./pages/administration/categories.html.twig', ['categories'=>$categories]);
  }

  public function categoryCreateAction(Request $request, EntityManagerInterface $em)
  {
    $form = $this->createForm(CategoryType::class);
    $form->handleRequest($request);
    if ($form->isSubmitted()) {
      $category = $form->getData();

      $em->persist($category);
      $em->flush();
      //$this->addFlash('success-category', 'La catégorie a bien été ajoutée !');
      return $this->redirectToRoute('admin-categories');
    }
    return $this->render('./pages/administration/categoryForm.html.twig', ['categoryForm' => $form->createView()]);
  }
}