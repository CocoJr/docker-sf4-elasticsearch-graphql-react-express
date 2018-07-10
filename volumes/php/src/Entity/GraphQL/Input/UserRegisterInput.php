<?php
/**
 * @author Thibault Colette <thibaultcolette06@hotmail.fr>
 * @copyright 2018 Thibault Colette
 */

namespace App\Entity\GraphQL\Input;

use Overblog\GraphQLBundle\Annotation as GQL;

/**
 * Class UserRegisterInput
 *
 * @GQL\GraphQLType(type="relay-mutation-input")
 */
class UserRegisterInput
{
    /**
     * @GQL\GraphQLColumn(type="string", nullable=false)
     */
    public $username;

    /**
     * @GQL\GraphQLColumn(type="string", nullable=false)
     */
    public $email;

    /**
     * @GQL\GraphQLColumn(type="string", nullable=false)
     */
    public $password;

    /**
     * @GQL\GraphQLColumn(type="string", nullable=false)
     */
    public $passwordConfirm;

    /**
     * @GQL\GraphQLColumn(type="bool", nullable=false)
     */
    public $termsAccepted;
}
