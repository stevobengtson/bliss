<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\Metadata\Post;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Transaction;
use App\Service\OwnerService;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class TransactionProcessor implements ProcessorInterface
{
    public function __construct(
        #[Autowire('api_platform.doctrine.orm.state.persist_processor')]
        private readonly ProcessorInterface $persistProcessor,
        private readonly OwnerService $ownerService
    )
    {
        
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        if ($data instanceof Transaction) {
            if ($operation instanceof Post) {
                $this->ownerService->setOwner($data);
                return $this->persistProcessor->process($data, $operation, $uriVariables, $context);
            }
        }
    }
}