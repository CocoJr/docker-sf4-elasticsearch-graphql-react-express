<?php
/**
 * @author Thibault Colette <thibaultcolette06@hotmail.fr>
 * @copyright 2018 Thibault Colette
 */

namespace App\GraphQL\Mutation;

use App\GraphQL\BaseGraphQLRequest;
use Overblog\GraphQLBundle\Definition\Resolver\MutationInterface;

abstract class BaseGraphQLMutation extends BaseGraphQLRequest implements MutationInterface
{
}