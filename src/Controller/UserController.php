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
                $this->addFlash("error", $errorMessages);
                return $this->redirect("/register");
            }
            // $userRepository->deleteUser($user); en attente de la fonction

            // Générer le token d'inscription
            $registrationToken = $this->generateMailToken();

            // Hacher le token et le définir pour l'utilisateur
            $user->setRegistrationToken(($registrationToken));
            //statut par defaut avant confirmation email
            $user->setIsConfirmed(false);
            //enregistre l'utilisateur dans la bdd
            $userRepository = new UserRepository();
            $userRepository->saveUser($user);
            //envoi email de confirmation

            $emailRenderer = new EmailRenderer();
            $emailService = new EmailConfirmation($emailRenderer);
            $emailService->sendEmail($user, 'Confirmez votre inscription', $registrationToken);
            $this->addFlash('success', 'Un email de confirmation vous a été envoyé.');
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
            $user = $userRepository->getUserBy('mail', $_POST["mail"]);

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

    public function confirmEmail(string $token): Response
    {
        $userRepository = new UserRepository();
        $user = $userRepository->getUserBy('registrationToken', $token);
        // Vérifiez si le token est valide

        if ($user) {
            // Mettez à jour isConfirmed à true
            $user->setIsConfirmed(true);
            // Mettez à jour le statut dans la base de données
            $userRepository->saveUser($user);

            $this->addFlash('success', 'Votre adresse e-mail a été confirmée avec succès. Vous pouvez maintenant vous connecter.');
            return $this->redirect('/user/login');
        } else {
            $this->addFlash('error', 'Le lien de confirmation n\'est pas valide.');
            return $this->redirect("/");
        }
    }

    protected function generateResetToken()
    {
        // Générez votre token de manière sécurisée ici (peut-être en utilisant une librairie dédiée)
        $resetToken = bin2hex(random_bytes(32));
        
        return $resetToken;
    }

    public function forgotPassword()
    {
        if ($this->isSubmitted("submit") && $this->isValided($_POST)) {
            // Récupérer l'utilisateur par son adresse e-mail
            $userEmail = $_POST['email']; // Assurez-vous de valider et filtrer les données ici
            $userRepository = new UserRepository();
            $user = $userRepository->getUserBy('email', $userEmail);
    
            if ($user) {
                // Générer un jeton de réinitialisation
                $resetToken = $this->generateResetToken();
    
                // Enregistrez le jeton dans la base de données pour cet utilisateur
                $user->setResetToken($resetToken);
                $userRepository->saveUser($user);
    
                // Envoyez un e-mail à l'utilisateur avec un lien contenant le jeton de réinitialisation
                $emailRenderer = new EmailRenderer();
                $emailService = new EmailConfirmation($emailRenderer);
                $emailService->sendResetEmail($user, 'Réinitialisez votre mot de passe', $resetToken);
    
                $this->addFlash('success', 'Un e-mail de réinitialisation a été envoyé à votre adresse e-mail.');
                return $this->redirect('/resetPassword' .$resetToken);
            } else {
                $this->addFlash('error', 'Aucun utilisateur trouvé avec cette adresse e-mail.');
            }
        }
    
        return $this->render('security/forgotPassword.html.twig');
    }
    
    
    public function resetPassword($resetToken)
{
    if ($this->isSubmitted("submit") && $this->isValided($_POST)) {
        // Récupérer l'utilisateur par le token de réinitialisation
        $userRepository = new UserRepository();
        $user = $userRepository->getUserBy('resetToken', $resetToken);

        if ($user) {
            // Mettre à jour le mot de passe de l'utilisateur
            $newPassword = $_POST["new_password"];
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $user->setPassword($hashedPassword);

            // Effacer le token de réinitialisation
            $user->setResetToken(null);
            $userRepository->saveUser($user);

            $this->addFlash('success', 'Votre mot de passe a été réinitialisé avec succès. Vous pouvez maintenant vous connecter avec votre nouveau mot de passe.');
            return $this->redirect('/login');
        } else {
            $this->addFlash('error', 'Le token de réinitialisation n\'est pas valide.');
            return $this->redirect('/forgot-password');
        }
    }

    return $this->render('security/resetPassword.html.twig', ['reset_link' => $resetToken]);
}

}
