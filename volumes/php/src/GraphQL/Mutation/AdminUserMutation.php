<?php
/**
 * @copyright 2018 Thibault Colette
 * @author Thibault Colette <thibaultcolette06@hotmail.fr>
 */

namespace App\GraphQL\Mutation;

use App\Entity\User;
use App\Manager\UserManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AdminUserMutation extends BaseGraphQLMutation
{
    /**
     * @param UserManager $userManager
     * @param $id
     * @param $username
     * @param $email
     * @param $plainPassword
     * @param $plainPasswordConfirm
     * @param $registratedAt
     * @param $enable
     *
     * @return array
     */
    public function edit(UserManager $userManager, $id, $username, $email, $plainPassword, $plainPasswordConfirm, $registratedAt, $enable): array
    {
        return $userManager->edit($username, $email, $plainPassword, $plainPasswordConfirm, $registratedAt, $enable, $id);
    }

    /**
     * @param UserManager $userManager
     * @param RequestStack $request
     * @param $id
     *
     * @return array
     */
    public function uploadImgProfil(UserManager $userManager, RequestStack $request, $id)
    {
        return $userManager->uploadImgProfil($request, $id);
    }
}