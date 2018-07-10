<?php
/**
 * @author Thibault Colette <thibaultcolette06@hotmail.fr>
 * @copyright 2018 Thibault Colette
 */

namespace App\Security;

use App\Entity\AuthToken;
use Doctrine\ORM\EntityManagerInterface;
use FOS\ElasticaBundle\Manager\RepositoryManagerInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\User;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

/**
 * Class AuthTokenUserProvider
 */
class AuthTokenUserProvider implements UserProviderInterface
{
    /** @var \App\Repository\AuthTokenRepository */
    protected $authTokenRepository;

    /** @var \App\Repository\ES\UserRepository */
    protected $userRepository;

    /**
     * AuthTokenUserProvider constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param RepositoryManagerInterface $repositoryManager
     */
    public function __construct(EntityManagerInterface $entityManager, RepositoryManagerInterface $repositoryManager)
    {
        $this->authTokenRepository = $entityManager->getRepository('App:AuthToken');
        $this->userRepository = $repositoryManager->getRepository('App:User');
    }

    /**
     * @param $authTokenHeader
     *
     * @return AuthToken|null
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getAuthToken($authTokenHeader)
    {
        return $this->authTokenRepository->findOneByValue($authTokenHeader);
    }

    /**
     * @param string $username
     *
     * @return \App\Entity\User|null
     */
    public function loadUserByUsername($username)
    {
        $user = $this->userRepository->loadUserByUsername($username);

        return $user;
    }

    /**
     * @param UserInterface $user
     *
     * @return UserInterface|void
     *
     * @codeCoverageIgnore
     */
    public function refreshUser(UserInterface $user)
    {
        throw new UnsupportedUserException();
    }

    /**
     * @param string $class
     *
     * @return bool
     */
    public function supportsClass($class)
    {
        return User::class === $class;
    }
}