<?php

namespace App\Twig\Components;

use App\Entity\Account;
use App\Entity\Transaction;
use App\Repository\TransactionRepository;
use Psr\Log\LoggerInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
use Symfony\UX\LiveComponent\Attribute\LiveListener;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
class TransactionList
{
    use DefaultActionTrait;

    #[LiveProp(writable: true)]
    public int $currentPage = 1;

    #[LiveProp]
    public int $perPage = 10;

    #[LiveProp]
    public Account $account;

    public function __construct(private readonly TransactionRepository $transactionRepository)
    {
    }

    #[LiveListener('paginationPageSelect')]
    public function reloadList(#[LiveArg] int $page): void
    {
        $this->currentPage = $page;
    }

    /**
     * @return Transaction[]
     */
    public function getTransactions(): array
    {
        return $this->transactionRepository->findBy(
            ['account' => $this->account],
            ['enteredDate' => 'desc'],
            $this->perPage,
            (($this->currentPage - 1) * $this->perPage)
        );
    }
}