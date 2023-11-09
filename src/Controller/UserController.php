<?php

namespace App\Controller;

use Core\component\AbstractController;
use App\Model\Entity\User;
use App\Model\Repository\UserRepository;
use App\Service\RegisterHandler;

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
                'password' => $_POST["password"],
                'checkpassword' => $_POST["checkpassword"],

            ];
            $registerHandler = new RegisterHandler();
            $errorMessages = $registerHandler->checkFields($userDatas);

            if (!empty($registerHandler->checkFields($userDatas))) {
                dd($errorMessages);
                //voir pour ajouter des parametres d'erreur 
                foreach ($errorMessages as $error) {
                    $this->addFlash("erreur lors de l'enregistrement", $error);
                }
                return $this->redirect("/register");
            }

            // Créer un objet User et UserRepository avec les données du formulaire
            $user = new User($userDatas);

            $userRepository = new UserRepository();
            $userRepository->saveUser($user);
            //user?
            // $userRepository->deleteUser($user);
            return $this->redirect("/");
        }

        return $this->render("security/register.html.twig");
    }

    public function loginUser()
    {
        if ($this->isSubmitted("submit") && $this->isValided($_POST)) {
            $this->newSession();
            // Valider les données de connexion
            // Vous pouvez ajouter ici la logique de validation du formulaire de connexion

            // Exemple : Vérifier si l'utilisateur existe dans la base de données
            $userRepository = new UserRepository();
            $user = $userRepository->getUser($_POST["mail"]);

            if ($user && password_verify($_POST["password"], $user->getPassword())) {
              
                // Connexion réussie
                // Vous pouvez implémenter ici la logique de connexion de l'utilisateur

                return $this->redirect("/");
            } else {

                // Afficher un message d'erreur en cas d'échec de connexion sans en mettre la raison par sécurité
                $this->addFlash("error", "identifiants invalides.");
                return $this->redirect("/user/login");
            }
        }

        return $this->render("security/login.html.twig");
    }
}
