<?php

namespace App\Controller;

use App\Entity\Budget;
use App\Entity\CategoryGroup;
use App\Form\CategoryGroupType;
use App\Repository\CategoryGroupRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/')]
class CategoryGroupController extends AbstractController
{
    #[Route('budget/{budget_id}/category_group', name: 'app_category_group_index', methods: ['GET'])]
    public function index(
        #[MapEntity(id: 'budget_id')]
        Budget $budget,
        CategoryGroupRepository $categoryGroupRepository
    ): Response {
        $categoryGroups = $categoryGroupRepository->findBy(['budget' => $budget]);

        return $this->render('category_group/index.html.twig', [
            'category_groups' => $categoryGroups,
            'budget' => $budget,
        ]);
    }

    #[Route('budget/{budget_id}/new', name: 'app_category_group_new', methods: ['GET', 'POST'])]
    public function new(
        #[MapEntity(id: 'budget_id')]
        Budget $budget,
        Request $request, 
        EntityManagerInterface $entityManager
    ): Response {
        $categoryGroup = new CategoryGroup();
        $categoryGroup->setBudget($budget);
        $categoryGroup->setOwner($budget->getOwner());

        $form = $this->createForm(CategoryGroupType::class, $categoryGroup);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($categoryGroup);
            $entityManager->flush();

            return $this->redirectToRoute('app_category_group_index', ['budget_id' => $budget->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('category_group/new.html.twig', [
            'category_group' => $categoryGroup,
            'budget' => $budget,
            'form' => $form,
        ]);
    }

    #[Route('category_group/{id}', name: 'app_category_group_show', methods: ['GET'])]
    public function show(CategoryGroup $categoryGroup): Response
    {
        return $this->render('category_group/show.html.twig', [
            'category_group' => $categoryGroup,
        ]);
    }

    #[Route('category_group/{id}/edit', name: 'app_category_group_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CategoryGroup $categoryGroup, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CategoryGroupType::class, $categoryGroup);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_category_group_index', ['budget_id' => $categoryGroup->getBudget()->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('category_group/edit.html.twig', [
            'category_group' => $categoryGroup,
            'form' => $form,
        ]);
    }

    #[Route('category_group/{id}', name: 'app_category_group_delete', methods: ['POST'])]
    public function delete(Request $request, CategoryGroup $categoryGroup, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$categoryGroup->getId(), $request->request->get('_token'))) {
            $entityManager->remove($categoryGroup);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_category_group_index', ['budget_id' => $categoryGroup->getBudget()], Response::HTTP_SEE_OTHER);
    }
}
