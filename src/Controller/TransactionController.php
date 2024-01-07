<?php

namespace App\Controller;

use App\Entity\Account;
use App\Entity\Transaction;
use App\Form\CreateTransactionType;
use App\Repository\TransactionRepository;
use App\Service\TransactionService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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

    #[Route('/account/{id}/transaction/new', name: 'app_account_transaction_create', methods: ['GET'])]
    public function new(Account $account): Response
    {
        $transaction = new Transaction();
        $transaction->setAccount($account);

        $form = $this->createForm(CreateTransactionType::class, $transaction);

        return $this->render('transaction/new.html.twig', [
            'form' => $form,
        ]);
    }
}
