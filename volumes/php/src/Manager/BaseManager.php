<?php

namespace App\Manager;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use FOS\ElasticaBundle\Manager\RepositoryManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class BaseManager
 *
 * @codeCoverageIgnore
 */
class BaseManager
{
    /** @var TokenStorageInterface */
    protected $tokenStorage;

    /** @var EntityManagerInterface */
    protected $entityManager;

    /** @var RepositoryManagerInterface */
    protected $repositoryManager;

    /** @var ValidatorInterface */
    protected $validator;

    /** @var TranslatorInterface */
    protected $translator;

    /**
     * BaseManager constructor.
     *
     * @param TokenStorageInterface $tokenStorage
     * @param EntityManagerInterface $entityManager
     * @param RepositoryManagerInterface $repositoryManager
     * @param ValidatorInterface $validator
     * @param TranslatorInterface $translator
     */
    public function __construct(TokenStorageInterface $tokenStorage, EntityManagerInterface $entityManager, RepositoryManagerInterface $repositoryManager, ValidatorInterface $validator, TranslatorInterface $translator)
    {
        $this->tokenStorage = $tokenStorage;
        $this->entityManager = $entityManager;
        $this->repositoryManager = $repositoryManager;
        $this->validator = $validator;
        $this->translator = $translator;
    }

    /**
     * Get the logged user
     *
     * @return User|null
     */
    protected function getUser()
    {
        $token = $this->tokenStorage->getToken();
        if ($token && $user = $token->getUser()) {
            if (!is_string($user) && strpos(get_class($user), User::class) !== false) {
                return $user;
            }
        }

        return null;
    }

    /**
     * Validate an entity and format error for graphQL FormError type
     *
     * @param object $entity
     * @param null|array $groups
     *
     * @return array
     */
    protected function validate($entity, $groups = null)
    {
        /** @var ConstraintViolationListInterface $errors */
        $errors = $this->validator->validate($entity, null, $groups);

        $errorsType = array();
        /** @var ConstraintViolation $e */
        foreach ($errors as $e) {
            $errorsType[] = array(
                'key' => $e->getPropertyPath(),
                'message' => $e->getMessage(),
            );
        }

        return $errorsType;
    }
}