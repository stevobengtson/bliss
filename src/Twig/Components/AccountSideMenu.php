<?php

namespace App\Twig\Components;

use App\Entity\Account;
use App\Repository\AccountRepository;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
class AccountSideMenu extends AbstractController
{
    public function __construct(private readonly AccountRepository $accountRepository, private readonly LoggerInterface $logger)
    {
    }

    /**
     * @return array<Account>
     */
    public function getAccounts(): array
    {
        $this->logger->debug("Getting user to display accounts for.");
        $user = $this->getUser();
        if (null === $user) {
            $this->logger->critical("Unable to find user!");
            return [];
        }

        $this->logger->debug("Found user getting list of accounts.");
        return $this->accountRepository->findBy(['owner' => $user]);
    }
}