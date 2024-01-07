<?php

namespace App\Service;

use App\Entity\Account;
use App\Entity\User;
use App\Repository\AccountRepository;
use Doctrine\ORM\EntityManagerInterface;

readonly class AccountService
{
    use PaginatorTrait;

    public function __construct(private AccountRepository $accountRepository, private EntityManagerInterface $entityManager)
    {
    }

    public function getAccountsForUser(User $user, int $page, int $itemsPerPage): PaginatorResults
    {
        $qb = $this->accountRepository->createQueryBuilder('a');

        $qb
            ->where($qb->expr()->eq('a.owner', '?1'))
            ->setParameter(1, $user->getId()->toRfc4122())
            ->orderBy('a.createdAt', 'DESC')
        ;

        return $this->getPagedResults($qb, $page, $itemsPerPage);
    }

    public function saveAccount(Account $account): void
    {
        $this->entityManager->persist($account);
        $this->entityManager->flush();
    }

    public function removeAccount(Account $account): void
    {
        $this->entityManager->remove($account);
        $this->entityManager->flush();
    }
}
