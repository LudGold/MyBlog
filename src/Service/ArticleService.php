<?php

namespace App\Service;

use App\Model\Repository\ArticleRepository;

class ArticleService
{
    private $articleRepository;

    public function __construct(ArticleRepository $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    public function getLatestArticles(int $limit)
    {
        $limit = 5; // Limite de 5 articles
        return $this->articleRepository->findLatestArticles($limit);
    }
}
