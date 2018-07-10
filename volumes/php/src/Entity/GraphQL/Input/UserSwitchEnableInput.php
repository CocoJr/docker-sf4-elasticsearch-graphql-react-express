<?php
/**
 * @copyright 2018 Thibault Colette
 * @author Thibault Colette <thibaultcolette06@hotmail.fr>
 */

namespace App\Entity\GraphQL\Input;

use Overblog\GraphQLBundle\Annotation as GQL;

/**
 * Class UserSwitchEnableInput
 *
 * @GQL\GraphQLType(type="relay-mutation-input")
 */
class UserSwitchEnableInput
{
    /**
     * @GQL\GraphQLColumn(type="ID", nullable=false)
     */
    public $id;

    /**
     * @GQL\GraphQLColumn(type="bool", nullable=false)
     */
    public $enable;
}
