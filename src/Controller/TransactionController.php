<?php

namespace App\Controller;

use App\Entity\Account;
use App\Entity\Transaction;
use App\Form\TransactionType;
use App\Service\TransactionService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;

class TransactionController extends AbstractController
{
    public function __construct(private readonly TransactionService $transactionService)
    {
    }

    #[Route('/account/{id}/transaction', name: 'app_account_transaction_index', methods: ['GET'])]
    public function index(
        Account $account,
        #[MapQueryParameter] int $page = 1,
        #[MapQueryParameter] int $itemsPerPage = 25
    ): Response {
        $transactions = $this->transactionService->getTransactionsForAccount($account, $page, $itemsPerPage);

        return $this->render('transaction/index.html.twig', [
            'account' => $account,
            'transactions' => $transactions,
        ]);
    }

    #[Route('/account/{id}/transaction/new', name: 'app_account_transaction_new', methods: ['GET', 'POST'])]
    public function new(Account $account, Request $request): Response
    {
        $transaction = new Transaction();
        $transaction->setAccount($account);

        $form = $this->createForm(TransactionType::class, $transaction, [
            'action' => $this->generateUrl('app_account_transaction_new', ['id' => $account->getId()]),
            'method' => 'POST',
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->transactionService->saveTransaction($transaction);

            return $this->redirectToRoute('app_account_transaction_index', ['id' => $account->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('transaction/new.html.twig', [
            'transaction' => $transaction,
            'form' => $form,
        ]);
    }

    #[Route('/transaction/{id}/edit', name: 'app_transaction_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Transaction $transaction): Response
    {
        $form = $this->createForm(TransactionType::class, $transaction);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->transactionService->saveTransaction($transaction);

            return $this->redirectToRoute('app_account_transaction_index', ['id' => $transaction->getAccount()->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('transaction/edit.html.twig', [
            'transaction' => $transaction,
            'form' => $form,
        ]);
    }

    #[Route('/transaction/{id}', name: 'app_transaction_delete', methods: ['POST'])]
    public function delete(Request $request, Transaction $transaction): Response
    {
        if ($this->isCsrfTokenValid('delete'.$transaction->getId(), $request->request->get('_token'))) {
            $this->transactionService->removeTransaction($transaction);
        }

        return $this->redirectToRoute('app_account_transaction_index', ['id' => $transaction->getAccount()->getId()], Response::HTTP_SEE_OTHER);
    }
}
