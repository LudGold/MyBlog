<?php

namespace App\Controller;

use Core\component\AbstractController;
use App\Model\Entity\User;
use App\Model\Repository\UserRepository;
use App\Service\RegisterHandler;
use App\Service\EmailConfirmation;
use Symfony\Component\HttpFoundation\Response;

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
            // Créer un objet User avec les données du formulaire
            $user = new User($userDatas);

            // on génére le token unique de confirmation du mail
            /* $registrationToken = bin2hex(random_bytes(32));
            $user ->setregistrationToken($registrationToken); */
            // valide les champs
            $registerHandler = new RegisterHandler();
            $errorMessages = $registerHandler->checkFields($userDatas);
            if (!empty($errorMessages)) {
                foreach ($errorMessages as $error) {
                    $this->addFlash("error", $error);
                }
                return $this->redirect("/register");
            }
            // $userRepository->deleteUser($user);
            //enregistre l'utilisateur avec le token
            $userRepository = new UserRepository();
            $userRepository->saveUser($user);
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
                $this->addFlash('success', 'Bienvenue');

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

    private $emailService;

    public function __construct(EmailConfirmation $emailService)
    {
        $this->emailService = $emailService;
    }

    public function sendMail(): Response
    {
        $this->emailService->sendEmail('recipient@example.com', 'Sujet', '<p>Contenu de l\'email</p>');
        
        // ...
        return new Response('Email sent!');
       
    }
    
}

