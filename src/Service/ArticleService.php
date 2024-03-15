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
       
        return $this->articleRepository->findLatestArticles($limit);
    }
}
