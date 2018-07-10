<?php
/**
 * @copyright 2018 Thibault Colette
 * @author Thibault Colette <thibaultcolette06@hotmail.fr>
 */

namespace App\Manager;

use App\Entity\AuthToken;
use App\Entity\User;

/**
 * Class AuthTokenManager
 *
 * @package App\Manager
 */
class AuthTokenManager extends BaseManager
{
    /**
     * @param User $user
     *
     * @return AuthToken
     *
     * @throws \Exception
     */
    public function create(User $user)
    {
        $authToken = new AuthToken();
        $authToken->setValue(base64_encode(random_bytes(50)))
            ->setCreatedAt(new \DateTime('now'))
            ->setUser($user);

        $user->addAuthToken($authToken);

        $this->entityManager->persist($authToken);
        $this->entityManager->flush();

        return $authToken;
    }
}