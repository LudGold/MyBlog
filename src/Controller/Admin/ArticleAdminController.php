<?php

namespace App\Controller\Admin;

use Core\component\AbstractController;

class ArticleAdminController extends AbstractController{
// page articles avec les differents infos, icone stylo et icon trash à droite
    public function index(){
        $this->isAdmin();


    }

}
