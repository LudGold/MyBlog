<?php

namespace App\Controller;

use Core\component\AbstractController;
use App\Model\Entity\User;
use App\Model\Repository\UserRepository;

class UserController extends AbstractController
{

    public function registerUser()
    {
        if ($this->isSubmitted("submit") && $this->isValided($_POST)) {
           
            // Récupérer les données du formulaire
            $userDatas = [
                'lastname' => $_POST["lastname"],
                'firstname' => $_POST["firstname"],
                'mail' => $_POST["mail"],
                'password' => $_POST["password"]
            ];

            // Créer un objet User et UserRepository avec les données du formulaire
            $user = new User($userDatas);
            
            $userRepository = new UserRepository();
            $userRepository->saveUser($user);
        
            return $this->redirect("/");
        }
        return $this->render("security/register.html.twig");
    }
}
