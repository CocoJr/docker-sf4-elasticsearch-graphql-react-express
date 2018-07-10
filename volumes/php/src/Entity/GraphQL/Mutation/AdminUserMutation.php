<?php
/**
 * @copyright 2018 Thibault Colette
 * @author Thibault Colette <thibaultcolette06@hotmail.fr>
 */

namespace App\Entity\GraphQL\Mutation;

use Overblog\GraphQLBundle\Annotation as GQL;

/**
 * Trait UserMutation
 */
trait AdminUserMutation
{
    /**
     * @GQL\GraphQLRelayMutation(
     *     input={"AdminUserEditProfilInput"},
     *     payload="UserPayload",
     *     method="App\\GraphQL\\Mutation\\AdminUserMutation::edit",
     *     args={
     *         "serv('App\\Manager\\UserManager')",
     *         "value['id']",
     *         "value['username']",
     *         "value['email']",
     *         "value['password']",
     *         "value['passwordConfirm']",
     *         "value['registratedAt']",
     *         "value['enable']"
     *     }
     * )
     */
    protected $adminUserEditProfil;

    /**
     * @GQL\GraphQLMutation(
     *     input={"AdminUserUploadImgProfilInput"},
     *     input={
     *         "id"="ID!",
     *         "file"="Upload!"
     *     },
     *     payload="UploadFilePayload",
     *     method="App\\GraphQL\\Mutation\\AdminUserMutation::uploadImgProfil",
     *     args={
     *         "serv('App\\Manager\\UserManager')",
     *         "serv('request_stack')",
     *         "args['id']"
     *     }
     * )
     */
    protected $adminUserUploadImgProfil;
}