<?php
/**
 * @author Thibault Colette <thibaultcolette06@hotmail.fr>
 * @copyright 2018 Thibault Colette
 */

namespace App\Entity\GraphQL\Mutation;

use Overblog\GraphQLBundle\Annotation as GQL;

/**
 * Trait UserMutation
 */
trait UserMutation
{
    /**
     * @GQL\GraphQLRelayMutation(
     *     input={"UserRegisterInput"},
     *     payload="UserPayload",
     *     method="App\\GraphQL\\Mutation\\UserMutation::create",
     *     args={
     *         "serv('App\\Manager\\UserManager')",
     *         "value['username']",
     *         "value['email']",
     *         "value['password']",
     *         "value['passwordConfirm']",
     *         "value['termsAccepted']"
     *     }
     * )
     */
    protected $userRegister;

    /**
     * @GQL\GraphQLRelayMutation(
     *     input={"UserLoginInput"},
     *     payload="AuthTokenPayload",
     *     method="App\\GraphQL\\Mutation\\UserMutation::login",
     *     args={
     *         "serv('App\\Manager\\UserManager')",
     *         "value['username']",
     *         "value['password']",
     *     }
     * )
     */
    protected $userLogin;

    /**
     * @GQL\GraphQLMutation(
     *     input={
     *         "file"="Upload!"
     *     },
     *     payload="UploadFilePayload",
     *     method="App\\GraphQL\\Mutation\\UserMutation::uploadImgProfil",
     *     args={
     *         "serv('App\\Manager\\UserManager')",
     *         "serv('request_stack')",
     *     }
     * )
     */
    protected $uploadImgProfil;

    /**
     * @GQL\GraphQLRelayMutation(
     *     input={"UserEditProfilInput"},
     *     payload="UserPayload",
     *     method="App\\GraphQL\\Mutation\\UserMutation::edit",
     *     args={
     *         "serv('App\\Manager\\UserManager')",
     *         "value['username']",
     *         "value['email']",
     *         "value['password']",
     *         "value['passwordConfirm']",
     *         "value['termsAccepted']"
     *     }
     * )
     */
    protected $userEditProfil;
}