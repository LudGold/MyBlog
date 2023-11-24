<?php

namespace App\Controller;

use Core\component\AbstractController;
use App\Model\Entity\User;
use App\Model\Repository\UserRepository;
use App\Service\RegisterHandler;
use App\Service\EmailConfirmation;
use App\Service\EmailRenderer;
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

            $registerHandler = new RegisterHandler();
            $errorMessages = $registerHandler->checkFields($userDatas);
            if (!empty($errorMessages)) {
                foreach ($errorMessages as $error) {
                    $this->addFlash("error", $error);
                }
                return $this->redirect("/register");
            }
            // $userRepository->deleteUser($user); en attente de la fonction

            // Générer le token d'inscription
            $registrationToken = $this->generateMailToken();
           
            // Hacher le token et le définir pour l'utilisateur
            $user->setRegistrationToken(password_hash($registrationToken, PASSWORD_DEFAULT));
            //statut par defaut avant confirmation email
            $user->setIsConfirmed(false);
            //enregistre l'utilisateur dans la bdd
            $userRepository = new UserRepository();
            $userRepository->saveUser($user);
            //envoi email de confirmation
            $emailRenderer = new EmailRenderer();
            $emailService = new EmailConfirmation($emailRenderer);
            $emailService->sendEmail($user, 'Confirmez votre inscription', $registrationToken);
            return $this->redirect("/");
        }
        return $this->render("security/register.html.twig");
    }

    public function loginUser()
    {
        if ($this->isSubmitted("submit") && $this->isValided($_POST)) {
            $this->newSession();
           
            // Valider les données de connexion
            //  ajouter ici la logique de validation du formulaire de connexion

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
    private function generateMailToken()
    {
        // Générez votre token de manière sécurisée ici (peut-être en utilisant une librairie dédiée)
        $token = bin2hex(random_bytes(32));

        return $token;
    }

    public function confirmEmail(User $user, string $token): Response
    {
        // Vérifiez si le token est valide
        if (password_verify($token, $user->getRegistrationToken())) {
            // Mettez à jour isConfirmed à true
            $user->setIsConfirmed(true);

            $this->addFlash('success', 'Votre adresse e-mail a été confirmée avec succès. Vous pouvez maintenant vous connecter.');
            return $this->redirect('confirmation');

        } else {
            $this->addFlash('error', 'Le lien de confirmation n\'est pas valide.');
            return $this->redirect("/");
        }
    }
}
