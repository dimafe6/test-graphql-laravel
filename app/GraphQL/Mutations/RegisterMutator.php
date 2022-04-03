<?php

namespace App\GraphQL\Mutations;

use App\Models\User;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\Hash;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class RegisterMutator
{
    public function resolve($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        /** @var User $user */
        $user = User::create([
            'name'     => $args['name'],
            'email'    => $args['email'],
            'password' => Hash::make($args['password'])
        ]);

        $user->markEmailAsVerified();

        $user->token = auth()->attempt(['email' => $args['email'], 'password' => $args['password']]);

        return $user;
    }
}
