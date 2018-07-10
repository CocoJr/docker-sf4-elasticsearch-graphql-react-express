<?php
/**
 * @author Thibault Colette <thibaultcolette06@hotmail.fr>
 * @copyright 2018 Thibault Colette
 */

namespace App\GraphQL\Resolver;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use FOS\ElasticaBundle\Manager\RepositoryManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class UserResolver extends BaseGraphQLResolver
{
    /**
     * @param TokenStorageInterface $tokenStorage
     *
     * @return User|null
     */
    public function getLogged(TokenStorageInterface $tokenStorage): ?User
    {
        return $this->getUser($tokenStorage);
    }

    /**
     * @param TokenStorageInterface $tokenStorage
     * @param EntityManagerInterface $entityManager
     *
     * @return User|null
     */
    public function logout(TokenStorageInterface $tokenStorage, EntityManagerInterface $entityManager): ?User
    {
        if ($connectedUser = $this->getUser($tokenStorage)) {
            $authTokens = $connectedUser->getAuthTokens();
            foreach ($authTokens as $authToken) {
                $entityManager->remove($authToken);
            }

            $entityManager->flush();

            return null;
        }

        return $connectedUser;
    }
}