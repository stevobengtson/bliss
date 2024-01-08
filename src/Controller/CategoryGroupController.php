<?php

namespace App\Controller;

use App\Entity\CategoryGroup;
use App\Form\CategoryGroupType;
use App\Repository\CategoryGroupRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/category_group')]
class CategoryGroupController extends AbstractController
{
    #[Route('/', name: 'app_category_group_index', methods: ['GET'])]
    public function index(CategoryGroupRepository $categoryGroupRepository): Response
    {
        return $this->render('category_group/index.html.twig', [
            'category_groups' => $categoryGroupRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_category_group_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $categoryGroup = new CategoryGroup();
        $form = $this->createForm(CategoryGroupType::class, $categoryGroup);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($categoryGroup);
            $entityManager->flush();

            return $this->redirectToRoute('app_category_group_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('category_group/new.html.twig', [
            'category_group' => $categoryGroup,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_category_group_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CategoryGroup $categoryGroup, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CategoryGroupType::class, $categoryGroup);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_category_group_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('category_group/edit.html.twig', [
            'category_group' => $categoryGroup,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_category_group_delete', methods: ['POST'])]
    public function delete(Request $request, CategoryGroup $categoryGroup, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$categoryGroup->getId(), $request->request->get('_token'))) {
            $entityManager->remove($categoryGroup);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_category_group_index', [], Response::HTTP_SEE_OTHER);
    }
}
