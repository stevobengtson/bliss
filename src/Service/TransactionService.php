<?php

namespace App\Service;

use App\Entity\Account;
use App\Entity\Transaction;
use App\Repository\TransactionRepository;
use Doctrine\ORM\EntityManagerInterface;

class TransactionService
{
    use PaginatorTrait;

    public function __construct(private TransactionRepository $transactionRepository, private EntityManagerInterface $entityManager)
    {
    }

    public function getTransactionsForAccount(Account $account, int $page, int $itemsPerPage): PaginatorResults
    {
        $qb = $this->transactionRepository->createQueryBuilder('t');

        $qb->where($qb->expr()->eq('t.account', '?1'))
            ->setParameter(1, $account->getId()->toRfc4122())
            ->orderBy('t.enteredDate', 'DESC')
        ;

        return $this->getPagedResults($qb, $page, $itemsPerPage);
    }

    public function saveTransaction(Transaction $transaction): void
    {
        $this->entityManager->persist($transaction);
        $this->entityManager->flush();
    }

    public function removeTransaction(Transaction $transaction): void
    {
        $this->entityManager->remove($transaction);
        $this->entityManager->flush();
    }
}
