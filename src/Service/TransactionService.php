<?php

namespace App\Service;

use App\Entity\Account;
use App\Entity\Transaction;
use App\Repository\TransactionRepository;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;

readonly class TransactionService
{
    public function __construct(private TransactionRepository $transactionRepository)
    {
    }

    /**
     * @param Account $account
     * @param string $sortBy
     * @param string $sortDirection
     * @param int $page
     * @param int $itemsPerPage
     *
     * @return Pagerfanta<Transaction>
     */
    public function getTransactions(
        Account $account,
        string $sortBy = 'entryDate',
        string $sortDirection = 'DESC',
        int $page = 1,
        int $itemsPerPage = 30
    ): Pagerfanta {
        $queryBuilder = $this->transactionRepository->createQueryBuilder('t');
        $queryBuilder->select('t')
            ->where('t.account = ?1')
            ->setParameter(1, $account->getId()->toRfc4122())
            ->orderBy("t.{$sortBy}", $sortDirection);

        return Pagerfanta::createForCurrentPageWithMaxPerPage(
            new QueryAdapter($queryBuilder),
            $page,
            $itemsPerPage
        );
    }
}
