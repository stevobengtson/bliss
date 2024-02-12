<?php

namespace App\Controller;

use App\Entity\Account;
use App\Entity\Transaction;
use App\Form\TransactionType;
use App\Service\TransactionService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/')]
class TransactionController extends AbstractController
{
    #[Route('/account/{account_id}/transaction', name: 'app_transaction_index', methods: ['GET'])]
    public function index(
        #[MapEntity(id: 'account_id')]
        Account $account,
        TransactionService $transactionService,
        #[MapQueryParameter] string $sortBy = 'entryDate',
        #[MapQueryParameter] string $sortDirection = 'DESC',
        #[MapQueryParameter] int $page = 1,
        #[MapQueryParameter] int $itemsPerPage = 15
    ): Response {
        $paginator = $transactionService->getTransactions($account, $sortBy, $sortDirection, $page, $itemsPerPage);

        return $this->render('transaction/index.html.twig', [
            'transactions' => $paginator,
            'account' => $account,
            'sortBy' => $sortBy,
            'sortDirection' => $sortDirection,
        ]);
    }

    #[Route('/account/{account_id}/transaction/new', name: 'app_transaction_new', methods: ['GET', 'POST'])]
    public function new(
        #[MapEntity(id: 'account_id')]
        Account $account,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $transaction = new Transaction();
        $transaction->setAccount($account);
        $transaction->setBudget($account->getBudget());

        $form = $this->createForm(TransactionType::class, $transaction);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($transaction);
            $entityManager->flush();

            return $this->redirectToRoute(
                'app_transaction_index',
                ['account_id' => $account->getId()],
                Response::HTTP_SEE_OTHER
            );
        }

        return $this->render('transaction/new.html.twig', [
            'transaction' => $transaction,
            'form' => $form,
            'account' => $account,
        ]);
    }

    #[Route('transaction/{id}/edit', name: 'app_transaction_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Transaction $transaction, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TransactionType::class, $transaction);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute(
                'app_transaction_index',
                ['account_id' => $transaction->getAccount()->getId()],
                Response::HTTP_SEE_OTHER
            );
        }

        return $this->render('transaction/edit.html.twig', [
            'transaction' => $transaction,
            'form' => $form,
        ]);
    }

    #[Route('transaction/{id}', name: 'app_transaction_delete', methods: ['POST'])]
    public function delete(Request $request, Transaction $transaction, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$transaction->getId(), $request->request->get('_token'))) {
            $entityManager->remove($transaction);
            $entityManager->flush();
        }

        return $this->redirectToRoute(
            'app_transaction_index',
            ['account_id' => $transaction->getAccount()->getId()],
            Response::HTTP_SEE_OTHER
        );
    }
}
