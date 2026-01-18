<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Article;

class ArticlePolicy
{
    /**
     * Determine if the user can view any articles.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('articles.view');
    }

    /**
     * Determine if the user can view the article.
     */
    public function view(User $user, Article $article): bool
    {
        // Published articles are viewable by anyone with articles.view permission
        if ($article->isPublished()) {
            return $user->can('articles.view');
        }

        // Draft/archived articles only viewable by author or those with edit permission
        return $user->id === $article->author_id || $user->can('articles.edit');
    }

    /**
     * Determine if the user can create articles.
     */
    public function create(User $user): bool
    {
        return $user->can('articles.create');
    }

    /**
     * Determine if the user can update the article.
     */
    public function update(User $user, Article $article): bool
    {
        // Author can always edit their own articles
        if ($user->id === $article->author_id) {
            return true;
        }

        // Others need explicit edit permission
        return $user->can('articles.edit');
    }

    /**
     * Determine if the user can delete the article.
     */
    public function delete(User $user, Article $article): bool
    {
        // Author can delete their own articles if they're not published
        if ($user->id === $article->author_id && $article->status !== 'published') {
            return true;
        }

        // Others need explicit delete permission
        return $user->can('articles.delete');
    }

    /**
     * Determine if the user can publish the article.
     */
    public function publish(User $user, Article $article): bool
    {
        return $user->can('articles.publish');
    }
}
