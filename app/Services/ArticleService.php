<?php

namespace App\Services;

use App\Models\Article;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Log;
use Nuwave\Lighthouse\Execution\Utils\Subscription;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ArticleService
{
    /**
     * @param Article $article
     * @param UploadedFile|null $image
     * @return mixed
     */
    private function saveArticleImage(Article $article, ?UploadedFile $image): mixed
    {
        if ($image) {
            $filename = md5($image->getClientOriginalName()) . "." . $image->getClientOriginalExtension();

            $image->storePubliclyAs('uploads', $filename, ['disk' => 'public']);

            $article->update(['image' => "uploads/$filename"]);
        }

        return false;
    }

    /**
     * @param Authenticatable $user
     * @param array $data
     * @return Article
     */
    public function create(Authenticatable $user, array $data)
    {
        $article = new Article($data);

        $user->articles()->save($article);

        $this->saveArticleImage($article, $data['image'] ?? null);

        Subscription::broadcast('articleCreated', $article);

        return $article;
    }

    /**
     * @param Authenticatable|User $user
     * @param int $articleId
     * @param array $data
     * @return Article
     */
    public function update(Authenticatable|User $user, int $articleId, array $data)
    {
        /** @var Article $article */
        $article = Article::find($articleId);

        if ($article && $user->can('update', $article)) {
            $article->update($data);

            $this->saveArticleImage($article, $data['image'] ?? null);

            Subscription::broadcast('articleUpdated', $article);
        }

        return $article;
    }

    /**
     * @param Authenticatable|User $user
     * @param int $articleId
     * @return array
     */
    public function delete(Authenticatable|User $user, int $articleId)
    {
        /** @var Article $article */
        $article = Article::find($articleId);

        $response = ['id' => $articleId];

        if ($article && $user->can('delete', $article)) {
            $article->delete();

            Subscription::broadcast('articleDeleted', $response);
        }

        return $response;
    }
}
