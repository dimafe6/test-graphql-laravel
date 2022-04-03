<?php

namespace App\GraphQL\Mutations;

use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Http\JsonResponse;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class AuthMutator
{
  /**
   * @param null $_
   * @param array<string, mixed> $args
   */
  public function resolve($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
  {

    if (!$token = auth()->attempt(collect($args)->only(['email', 'password'])->toArray())) {
      throw new AccessDeniedHttpException('rwewe');
    }

    $user = auth()->user();
    $user->token = $token;

    return $user;
  }
}
