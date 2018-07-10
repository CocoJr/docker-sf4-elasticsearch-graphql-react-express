<?php

namespace App\Manager;

use App\Entity\AuthToken;
use App\Entity\File;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use FOS\ElasticaBundle\Manager\RepositoryManagerInterface;
use Stof\DoctrineExtensionsBundle\Uploadable\UploadableManager;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserManager extends BaseManager
{
    /**
     * @var UserPasswordEncoderInterface
     */
    protected $passwordEncoder;

    /**
     * @var ValidatorInterface
     */
    protected $validator;

    /** @var UploadableManager */
    protected $uploadableManager;

    /** @var AuthTokenManager */
    protected $authTokenManager;

    /**
     * UserManager constructor.
     *
     * @param TokenStorageInterface $tokenStorage
     * @param EntityManagerInterface $entityManager
     * @param RepositoryManagerInterface $repositoryManager
     * @param ValidatorInterface $validator
     * @param TranslatorInterface $translator
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param UploadableManager $uploadableManager
     */
    public function __construct(TokenStorageInterface $tokenStorage, EntityManagerInterface $entityManager, RepositoryManagerInterface $repositoryManager, ValidatorInterface $validator, TranslatorInterface $translator, UserPasswordEncoderInterface $passwordEncoder, UploadableManager $uploadableManager, AuthTokenManager $authTokenManager)
    {
        parent::__construct($tokenStorage, $entityManager, $repositoryManager, $validator, $translator);

        $this->passwordEncoder = $passwordEncoder;
        $this->uploadableManager = $uploadableManager;
        $this->authTokenManager = $authTokenManager;
    }

    /**
     * @param $username
     * @param $email
     * @param $plainPassword
     * @param $plainPasswordConfirm
     * @param $termsAccepted
     *
     * @return array
     */
    public function create($username, $email, $plainPassword, $plainPasswordConfirm, $termsAccepted)
    {
        $user = new User();
        $user->setEmail($email);
        $user->setUsername($username);
        $user->setPlainPassword($plainPassword);
        $user->setPasswordConfirm($plainPasswordConfirm);
        $user->setTermsAccepted($termsAccepted);

        if (empty($errors = $this->validate($user, ['Default', 'user_register']))) {
            $password = $this->passwordEncoder->encodePassword($user, $plainPassword);

            $user->setPassword($password);
            $user->setTermsAcceptedAt(new \DateTime());

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $this->entityManager->refresh($user);
        }

        return [
            'user' => !empty($errors) ? null : $user,
            'errors' => $errors,
        ];
    }

    /**
     * @param $username
     * @param $email
     * @param $plainPassword
     * @param $plainPasswordConfirm
     * @param null $registratedAt
     * @param null $enable
     * @param null $id
     *
     * @return array
     */
    public function edit($username, $email, $plainPassword, $plainPasswordConfirm, $registratedAt = null, $enable = null, $id = null)
    {
        $user = $this->getUser();
        if ($id) {
            if (!$user->hasRole('ROLE_ADMIN')) {
                throw new AccessDeniedException();
            }

            if (!$user = $this->entityManager->getRepository('App:User')->find($id)) {
                return [
                    'user' => $user,
                    'errors' => [
                        ['key' => null, 'message' => $this->translator->trans('user.admin.not_found', [], 'validators')],
                    ],
                ];
            }
        }

        if (!is_null($email)) {
            $user->setEmail($email);
        }
        if (!is_null($username)) {
            $user->setUsername($username);
        }
        if (!is_null($plainPassword)) {
            $user->setPlainPassword($plainPassword);
            $user->setPasswordConfirm($plainPasswordConfirm);
        }
        if (!is_null($registratedAt)) {
            $user->setRegistratedAt($registratedAt);
        }
        if (!is_null($enable)) {
            $user->setIsActive($enable);
        }

        if (empty($errors = $this->validate($user))) {
            if ($plainPassword) {
                $password = $this->passwordEncoder->encodePassword($user, $plainPassword);
                $user->setPassword($password);
            }

            $this->entityManager->flush();

            $this->entityManager->refresh($user);
        }

        return [
            'user' => $user,
            'errors' => $errors,
        ];
    }

    /**
     * Login an user
     *
     * @param $username
     * @param $password
     *
     * @return array
     *
     * @throws \Exception
     */
    public function login($username, $password)
    {
        $errorsType = array();

        /** @var null|User $user */
        $user = null;
        $user = $this->repositoryManager
            ->getRepository('App:User')
            ->loadUserByUsername($username);

        if (!$user) {
            $errorsType[] = ['message' => $this->translator->trans('error.username_or_password', [], 'validators')];
        }

        if ($user && !$isPasswordValid = $this->passwordEncoder->isPasswordValid($user, $password)) {
            $user = null;
            $errorsType[] = ['message' => $this->translator->trans('error.username_or_password', [], 'validators')];
        }

        if ($user && !$user->isEnabled()) {
            $user = null;
            $errorsType[] = ['message' => $this->translator->trans('error.user_is_disable', [], 'validators')];
        }

        $authToken = null;
        if ($user) {
            $authToken = $this->authTokenManager->create($user);
        }

        return [
            'token' => $authToken,
            'errors' => $errorsType,
        ];
    }

    /**
     * @param RequestStack $request
     * @param null $id
     *
     * @return array|File
     */
    public function uploadImgProfil(RequestStack $request, $id = null)
    {
        $user = $this->getUser();
        if ($id) {
            if (!$user->hasRole('ROLE_ADMIN')) {
                throw new AccessDeniedException();
            }

            if (!$user = $this->entityManager->getRepository('App:User')->find($id)) {
                return [
                    'file' => null,
                    'errors' => [
                        ['key' => null, 'message' => $this->translator->trans('user.admin.not_found', [], 'validators')],
                    ],
                ];
            }
        }
        $request = $request->getMasterRequest() ?? $request->getCurrentRequest();

        /** @var \Symfony\Component\HttpFoundation\FileBag; $requestFiles */
        $requestFiles = $request->files;
        if (count($requestFiles) !== 1) {
            return [
                'file' => null,
                'errors' => [
                    ['key' => 'imgProfil.file', 'message' => $this->translator->trans('This file is not a valid image.', [], 'validators')],
                ],
            ];
        }

        $oldFile = $user->getImgProfil();

        $file = new File();
        $file->file = $requestFiles->getIterator()->current();
        $user->setImgProfil($file);
        if (empty($errors = $this->validate($user, ['upload_img']))) {
            $this->uploadableManager->markEntityToUpload($file, $file->file);

            $this->entityManager->persist($file);

            if ($oldFile) {
                $this->entityManager->remove($oldFile);
                $this->uploadableManager->getUploadableListener()->removeFile($oldFile->getPath());
            }

            $this->entityManager->flush();

            return [
                'file' => $file,
                'errors' => [],
            ];
        }

        return [
            'file' => null,
            'errors' => $errors,
        ];
    }
}