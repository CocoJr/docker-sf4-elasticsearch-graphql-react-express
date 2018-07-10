<?php
/**
 * @author Thibault Colette <thibaultcolette06@hotmail.fr>
 * @copyright 2018 Thibault Colette
 */

namespace App\Entity\GraphQL\Input;

use Overblog\GraphQLBundle\Annotation as GQL;

/**
 * Class UserLoginInput
 *
 * @GQL\GraphQLType(type="relay-mutation-input")
 */
class UserLoginInput
{
    /**
     * @GQL\GraphQLColumn(type="string", nullable=false)
     */
    public $username;

    /**
     * @GQL\GraphQLColumn(type="string")
     */
    public $password;
}
