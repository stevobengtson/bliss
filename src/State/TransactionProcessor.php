<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class TransactionProcessor implements ProcessorInterface
{
    public function __construct(
        #[Autowire('api_platform.doctrine.orm.state.persist_processor')]
        private readonly ProcessorInterface $persistProcessor
    )
    {
        
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [])
    {
    }
}