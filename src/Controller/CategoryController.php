<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\CategoryGroup;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/')]
class CategoryController extends AbstractController
{
    #[Route('/category_group/{category_group_id}/category', name: 'app_category_index', methods: ['GET'])]
    public function index(
        #[MapEntity(id: 'category_group_id')]
        CategoryGroup $categoryGroup,
        CategoryRepository $categoryRepository
    ): Response {
        $categories = $categoryRepository->findBy(['categoryGroup' => $categoryGroup]);

        return $this->render('category/index.html.twig', [
            'categories' => $categories,
            'categoryGroup' => $categoryGroup,
        ]);
    }

    #[Route('/category_group/{category_group_id}/category/new', name: 'app_category_new', methods: ['GET', 'POST'])]
    public function new(
        #[MapEntity(id: 'category_group_id')]
        CategoryGroup $categoryGroup,        
        Request $request, EntityManagerInterface $entityManager
    ): Response {
        $category = new Category();
        $category->setCategoryGroup($categoryGroup);
        $category->setBudget($categoryGroup->getBudget());

        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($category);
            $entityManager->flush();

            return $this->redirectToRoute('app_category_index', ['category_group_id' => $categoryGroup->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('category/new.html.twig', [
            'category' => $category,
            'form' => $form,
        ]);
    }

    #[Route('category/{id}', name: 'app_category_show', methods: ['GET'])]
    public function show(Category $category): Response
    {
        return $this->render('category/show.html.twig', [
            'category' => $category,
        ]);
    }

    #[Route('category/{id}/edit', name: 'app_category_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Category $category, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_category_index', ['category_group_id' => $category->getCategoryGroup()->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('category/edit.html.twig', [
            'category' => $category,
            'form' => $form,
        ]);
    }

    #[Route('category/{id}', name: 'app_category_delete', methods: ['POST'])]
    public function delete(Request $request, Category $category, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$category->getId(), $request->request->get('_token'))) {
            $entityManager->remove($category);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_category_index', ['category_group_id' => $category->getCategoryGroup()->getId()], Response::HTTP_SEE_OTHER);
    }
}
