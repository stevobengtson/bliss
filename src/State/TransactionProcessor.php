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

    /**
     * Handle the state.
     *
     * @param array<string, mixed>                                                                                                                                   $uriVariables
     * @param array<string, mixed>&array{request?: \Symfony\Component\HttpFoundation\Request, previous_data?: mixed, resource_class?: string, original_data?: mixed} $context
     *
     * @return T
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [])
    {
    }
}