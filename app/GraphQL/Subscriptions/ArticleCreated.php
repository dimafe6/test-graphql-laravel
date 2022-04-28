<?php

namespace App\GraphQL\Subscriptions;

use App\Models\Article;
use App\Models\User;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Http\Request;
use Nuwave\Lighthouse\Schema\Types\GraphQLSubscription;
use Nuwave\Lighthouse\Subscriptions\Subscriber;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class ArticleCreated extends GraphQLSubscription
{
    /**
     * Check if subscriber is allowed to listen to the subscription.
     *
     * @param Subscriber $subscriber
     * @param Request $request
     * @return bool
     */
    public function authorize(Subscriber $subscriber, Request $request): bool
    {
        /** @var User $user */
        $user = $subscriber->context->user;

        //return $user->can('viewArticles', $author);

        return true;
    }

    /**
     * Filter which subscribers should receive the subscription.
     *
     * @param Subscriber $subscriber
     * @param mixed $root
     * @return bool
     */
    public function filter(Subscriber $subscriber, $root): bool
    {
        /** @var User $user */
        $user = $subscriber->context->user;

        // Don't broadcast the subscription to the same
        // person who updated the article.
        //return $root->updated_by !== $user->id;

        return true;
    }

    /**
     * Resolve the subscription.
     *
     * @param Article $root
     * @param array<string, mixed> $args
     * @param GraphQLContext $context
     * @param ResolveInfo $resolveInfo
     * @return mixed
     */
    public function resolve($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): Article
    {
        // Optionally manipulate the `$root` item before it gets broadcasted to
        // subscribed client(s).
        $root->load(['user']);

        return $root;
    }
}
