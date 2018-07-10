<?php
/**
 * @author Thibault Colette <thibaultcolette06@hotmail.fr>
 * @copyright 2018 Thibault Colette
 */

namespace App\GraphQL;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class BaseGraphQLRequest
 */
class BaseGraphQLRequest
{
    /**
     * @param TokenStorageInterface $tokenStorage
     *
     * @return User|null
     */
    protected function getUser(TokenStorageInterface $tokenStorage)
    {
        $token = $tokenStorage->getToken();
        if ($token && $user = $token->getUser()) {
            if (!is_string($user) && strpos(get_class($user), User::class) !== false) {
                return $user;
            }
        }

        return null;
    }
}