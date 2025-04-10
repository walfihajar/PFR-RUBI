<?php

namespace App\Repositories\Interfaces;

interface ArticleRepositoryInterface
{
    public function getAllArticles();

    public function getArticleById($id);

    public function getArticlesByStatus($status);

    public function createArticle(array $articleData);

    public function updateArticle($id, array $newDetails);

    public function deleteArticle($id);

    public function getArticleStats();
}
