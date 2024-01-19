<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\Metadata\Post;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Budget;
use App\Service\OwnerService;

class BudgetProcessor implements ProcessorInterface
{
    public function __construct(
        private readonly ProcessorInterface $persistProcessor,
        private readonly OwnerService      $ownerService
    ) {
    }

    /**
     * @inheritDoc
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): void
    {
        if ($data instanceof Budget) {
            if ($operation instanceof Post) {
                $this->ownerService->setOwner($data);
                $this->persistProcessor->process($data, $operation, $uriVariables, $context);
            }
        }
    }
}
