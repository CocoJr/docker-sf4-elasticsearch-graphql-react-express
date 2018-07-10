<?php
/**
 * @author Thibault Colette <thibaultcolette06@hotmail.fr>
 * @copyright 2018 Thibault Colette
 */

namespace App\GraphQL\Mutation;

use App\Entity\AuthToken;
use App\Entity\User;
use App\Entity\File;
use App\Manager\UserManager;
use Doctrine\ORM\EntityManagerInterface;
use FOS\ElasticaBundle\Manager\RepositoryManagerInterface;
use Stof\DoctrineExtensionsBundle\Uploadable\UploadableManager;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserMutation extends BaseGraphQLMutation
{
    /**
     * @param UserManager $userManager
     * @param $username
     * @param $email
     * @param $plainPassword
     * @param $plainPasswordConfirm
     * @param $termsAccepted
     *
     * @return array
     */
    public function create(UserManager $userManager, $username, $email, $plainPassword, $plainPasswordConfirm, $termsAccepted): array
    {
        return $userManager->create($username, $email, $plainPassword, $plainPasswordConfirm, $termsAccepted);
    }

    /**
     * @param UserManager $userManager
     * @param string $username
     * @param string $email
     * @param string $plainPassword
     * @param string $plainPasswordConfirm
     *
     * @return array
     */
    public function edit(UserManager $userManager, $username, $email, $plainPassword, $plainPasswordConfirm): array
    {
        return $userManager->edit($username, $email, $plainPassword, $plainPasswordConfirm);
    }

    /**
     * @param UserManager $userManager
     * @param $username
     * @param $password
     *
     * @return array
     *
     * @throws \Exception
     */
    public function login(UserManager $userManager, $username, $password)
    {
        return $userManager->login($username, $password);
    }

    /**
     * @param UserManager $userManager
     * @param RequestStack $request
     *
     * @return array
     */
    public function uploadImgProfil(UserManager $userManager, RequestStack $request)
    {
        return $userManager->uploadImgProfil($request);
    }
}