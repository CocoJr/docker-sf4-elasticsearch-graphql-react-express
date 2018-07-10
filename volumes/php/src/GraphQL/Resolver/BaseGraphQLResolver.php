<?php
/**
 * @author Thibault Colette <thibaultcolette06@hotmail.fr>
 * @copyright 2018 Thibault Colette
 */

namespace App\GraphQL\Resolver;

use App\GraphQL\BaseGraphQLRequest;
use Overblog\GraphQLBundle\Definition\Resolver\ResolverInterface;

abstract class BaseGraphQLResolver extends BaseGraphQLRequest implements ResolverInterface
{
}