<?php

namespace App\Controller\Admin;

use Core\component\AbstractController;
use App\Model\Repository\ArticleRepository;

class ArticleAdminController extends AbstractController
{


    // page articles avec les differents infos, icone stylo et icon trash à droite
    public function index()
    {
        $this->isAdmin();

        $articleRepository = new ArticleRepository();
        $articles = $articleRepository->getAllArticles();

        return $this->render("admin/article/index.html.twig", ['articles' => $articles]);
    }
}
