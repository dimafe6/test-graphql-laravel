<?php

namespace App\GraphQL\Subscriptions;

use App\Models\Article;
use App\Models\User;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Nuwave\Lighthouse\Schema\Types\GraphQLSubscription;
use Nuwave\Lighthouse\Subscriptions\Subscriber;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class ArticleUpdated extends GraphQLSubscription
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
     * Encode topic name.
     *
     * @param Subscriber $subscriber
     * @param string $fieldName
     * @return string
     */
    public function encodeTopic(Subscriber $subscriber, string $fieldName): string
    {
        // Create a unique topic name
        return Str::snake($fieldName);
    }

    /**
     * Decode topic name.
     *
     * @param string $fieldName
     * @param Article $root
     * @return string
     */
    public function decodeTopic(string $fieldName, $root): string
    {
        return Str::snake($fieldName);
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
