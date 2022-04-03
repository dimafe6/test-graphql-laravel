<?php

namespace App\GraphQL\Mutations;

use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Contracts\Auth\Authenticatable;
use Nuwave\Lighthouse\Exceptions\AuthenticationException;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class AuthMutator
{
    /**
     * @param $rootValue
     * @param array<string, mixed> $args
     * @param GraphQLContext $context
     * @param ResolveInfo $resolveInfo
     * @return Authenticatable|null
     */
    public function resolve($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        if (!$token = auth()->attempt(collect($args['input'])->only(['email', 'password'])->toArray())) {
            throw new AuthenticationException('Invalid credentials');
        }

        $user = auth()->user();
        $user->token = $token;

        return $user;
    }
}
