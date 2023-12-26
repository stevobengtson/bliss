<?php

namespace App\Twig\Components;

use App\Entity\Account;
use App\Repository\AccountRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
readonly class AccountSideMenu
{
    public function __construct(
        private AccountRepository $accountRepository,
        private Security          $security
    ) {
    }

    /**
     * @return array<Account>
     */
    public function getAccounts(): array
    {
        $user = $this->security->getUser();
        if (null === $user) {
            return [];
        }

        return $this->accountRepository->findBy(['owner' => $user]);
    }
}