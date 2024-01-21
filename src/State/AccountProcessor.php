<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\Metadata\Post;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Account;
use App\Service\OwnerService;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

/**
 * @implements ProcessorInterface<Account, Account|void>
 */
class AccountProcessor implements ProcessorInterface
{
    public function __construct(
        #[Autowire('api_platform.doctrine.orm.state.item_provider')]
        private readonly ProcessorInterface $persistProcessor,
        private readonly OwnerService       $ownerService
    )
    {
    }

    /**
     * @inheritDoc
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): void
    {
        if ($data instanceof Account) {
            if ($operation instanceof Post) {
                $this->ownerService->setOwner($data);
                $this->persistProcessor->process($data, $operation, $uriVariables, $context);
            }
        }
    }
}
