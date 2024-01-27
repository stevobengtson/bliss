<?php

namespace App\Controller;

use App\Entity\Account;
use App\Entity\Budget;
use App\Form\AccountType;
use App\Repository\AccountRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/')]
class AccountController extends AbstractController
{
    #[Route('budget/{budget_id}/account', name: 'app_account_index', methods: ['GET'])]
    public function index(
        #[MapEntity(id: 'budget_id')]
        Budget $budget,
        AccountRepository $accountRepository
    ): Response {
        $accounts = $accountRepository->findBy(['budget' => $budget]);
        return $this->render('account/index.html.twig', [
            'accounts' => $accounts,
            'budget' => $budget,
        ]);
    }

    #[Route('budget/{budget_id}/account/new', name: 'app_account_new', methods: ['GET', 'POST'])]
    public function new(
        #[MapEntity(id: 'budget_id')]
        Budget $budget,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $account = new Account();
        $account->setBudget($budget);

        $form = $this->createForm(AccountType::class, $account);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($account);
            $entityManager->flush();

            return $this->redirectToRoute('app_account_index', ['budget_id' => $budget->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('account/new.html.twig', [
            'account' => $account,
            'form' => $form,
            'budget' => $budget,
        ]);
    }

    #[Route('account/{id}', name: 'app_account_show', methods: ['GET'])]
    public function show(Account $account): Response
    {
        return $this->render('account/show.html.twig', [
            'account' => $account,
        ]);
    }

    #[Route('account/{id}/edit', name: 'app_account_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Account $account, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AccountType::class, $account);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_account_index', ['budget_id' => $account->getBudget()->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('account/edit.html.twig', [
            'account' => $account,
            'form' => $form,
        ]);
    }

    #[Route('account/{id}', name: 'app_account_delete', methods: ['POST'])]
    public function delete(Request $request, Account $account, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $account->getId(), $request->request->get('_token'))) {
            $entityManager->remove($account);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_account_index', ['budget_id' => $account->getBudget()->getId()], Response::HTTP_SEE_OTHER);
    }
}
