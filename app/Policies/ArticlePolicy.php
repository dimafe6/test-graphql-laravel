<?php

namespace App\Policies;

use App\Models\Article;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ArticlePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param User $user
     * @return bool
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @param Article $article
     * @return bool
     */
    public function view(User $user, Article $article)
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @return bool
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param Article $article
     * @return bool
     */
    public function update(User $user, Article $article)
    {
        return (int)$article->user_id === (int)$user->id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param Article $article
     * @return bool
     */
    public function delete(User $user, Article $article)
    {
        return (int)$article->user_id === (int)$user->id;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User $user
     * @param Article $article
     * @return bool
     */
    public function restore(User $user, Article $article)
    {
        return $article->user_id === $user->id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User $user
     * @param Article $article
     * @return bool
     */
    public function forceDelete(User $user, Article $article)
    {
        return $article->user_id === $user->id;
    }
}
