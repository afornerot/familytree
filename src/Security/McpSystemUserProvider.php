<?php

namespace App\Security;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class McpSystemUserProvider implements UserProviderInterface
{
    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        if ('mcp-system' !== $identifier) {
            throw new \Symfony\Component\Security\Core\Exception\UsernameNotFoundException();
        }

        return new McpSystemUser();
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        return $user;
    }

    public function supportsClass(string $class): bool
    {
        return McpSystemUser::class === $class;
    }
}
