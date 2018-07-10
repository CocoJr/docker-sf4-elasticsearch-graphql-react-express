<?php

namespace App\Entity\GraphQL\Type;

use Overblog\GraphQLBundle\Annotation as GQL;

/**
 * Class DatetimeType
 *
 * @GQL\GraphQLAlias(name="datetime")
 * @GQL\GraphQLType(type="custom-scalar")
 */
class DatetimeType
{
    /**
     * @param \DateTime $value
     *
     * @return string
     */
    public static function serialize(\DateTime $value)
    {
        return $value->format(\DATE_ATOM);
    }

    /**
     * @param mixed $value
     *
     * @return mixed
     */
    public static function parseValue($value)
    {
        return new \DateTime($value);
    }

    /**
     * @param Node $valueNode
     *
     * @return string
     */
    public static function parseLiteral($valueNode)
    {
        return new \DateTime($valueNode->value);
    }
}