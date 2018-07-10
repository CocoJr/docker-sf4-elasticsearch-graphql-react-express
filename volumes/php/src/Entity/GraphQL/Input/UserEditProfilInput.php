<?php
/**
 * @copyright 2018 Thibault Colette
 * @author Thibault Colette <thibaultcolette06@hotmail.fr>
 */

namespace App\Entity\GraphQL\Input;

use Overblog\GraphQLBundle\Annotation as GQL;

/**
 * Class UserEditProfilInput
 *
 * @GQL\GraphQLType(type="relay-mutation-input")
 */
class UserEditProfilInput
{
    /**
     * @GQL\GraphQLColumn(type="string", nullable=true)
     */
    public $username;

    /**
     * @GQL\GraphQLColumn(type="string", nullable=true)
     */
    public $email;

    /**
     * @GQL\GraphQLColumn(type="string", nullable=true)
     */
    public $password;

    /**
     * @GQL\GraphQLColumn(type="string", nullable=true)
     */
    public $passwordConfirm;
}
