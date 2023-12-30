<?php

namespace App\Controller;

use App\Entity\Account;
use App\Repository\TransactionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Annotation\Route;

class TransactionController extends AbstractController
{
    public function __construct(private readonly TransactionRepository $transactionRepository)
    {}

    #[Route('/accounts/{id}/transactions', name: 'app_account_transactions')]
    public function index(
        Account $account,
        #[MapQueryParameter] int $page = 1,
        #[MapQueryParameter] int $perPage = 10,
        #[MapQueryParameter] bool $listOnly = false
    ): Response {
        $criteria = ['account' => $account];
        $maxPages = $this->transactionRepository->count($criteria) / $perPage;
        $transactions = $this->transactionRepository->findBy(
            $criteria,
            ['enteredDate' => 'desc'],
            $perPage,
            (($page - 1) * $perPage)
        );

        if ($listOnly) {
            return $this->render('transaction/_list.html.twig', [
                'transactions' => $transactions,
            ]);
        }

        return $this->render('transaction/index.html.twig', [
            'account' => $account,
            'transactions' => $transactions,
            'page' => $page,
            'perPage' => $perPage,
            'maxPages' => $maxPages,
        ]);
    }
}
