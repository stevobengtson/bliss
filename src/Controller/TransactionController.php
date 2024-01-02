<?php

namespace App\Controller;

use App\Entity\Account;
use App\Repository\TransactionRepository;
use App\Twig\Components\Pagination;
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
        #[MapQueryParameter] int $perPage = 10
    ): Response {
        $criteria = ['account' => $account];
        $maxPages = round($this->transactionRepository->count($criteria) / $perPage, 0, PHP_ROUND_HALF_DOWN);

        return $this->render('transaction/index.html.twig', [
            'account' => $account,
            'page' => $page,
            'perPage' => $perPage,
            'maxPages' => $maxPages,
        ]);
    }
}
