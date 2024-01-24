<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\User;
use App\Service\UserService;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

/**
 * @implements ProcessorInterface<User, User|void>
 */
final class UserPasswordHasher implements ProcessorInterface
{
    public function __construct(
        #[Autowire(service: 'api_platform.doctrine.orm.state.persist_processor')]
        private ProcessorInterface $persistProcessor,
        private UserService        $userService
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
    public function process($data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        if ($data instanceof User) {
            $this->userService->hashUserPassword($data);
            return $this->persistProcessor->process($data, $operation, $uriVariables, $context);
        }
    }
}
