<?php
/**
 * @author Thibault Colette <thibaultcolette06@hotmail.fr>
 * @copyright 2018 Thibault Colette
 */

namespace App\Entity\GraphQL\Type;

use GraphQL\Language\AST\Node;
use Overblog\GraphQLBundle\Annotation as GQL;

/**
 * Class ArrayType
 *
 * @GQL\GraphQLAlias(name="array")
 * @GQL\GraphQLType(type="custom-scalar")
 * )
 */
class ArrayType
{
    /**
     * @param array $value
     *
     * @return string
     */
    public static function serialize(array $value)
    {
        return json_encode($value);
    }

    /**
     * @param string $value
     *
     * @return array
     */
    public static function parseValue($value)
    {
        return json_decode($value, true);
    }

    /**
     * @param Node $valueNode
     *
     * @return string
     */
    public static function parseLiteral($valueNode)
    {
        return json_decode($valueNode->value, true);
    }
}