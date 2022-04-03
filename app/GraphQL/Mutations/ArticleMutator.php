<?php

namespace App\GraphQL\Mutations;

use App\Services\ArticleService;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class ArticleMutator
{
    private ArticleService $service;

    public function __construct(ArticleService $service)
    {
        $this->service = $service;
    }

    /**
     * Return a value for the field.
     *
     * @param $rootValue
     * @param array $args
     * @param GraphQLContext $context
     * @return mixed
     */
    public function create($rootValue, array $args, GraphQLContext $context)
    {
        return $this->service->create($context->user(), $args);
    }

    /**
     * Return a value for the field.
     *
     * @param $rootValue
     * @param array $args
     * @param GraphQLContext $context
     * @return mixed
     */
    public function update($rootValue, array $args, GraphQLContext $context)
    {
        return $this->service->update($context->user(), $args['id'], $args);
    }
}
