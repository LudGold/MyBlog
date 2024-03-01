<?php

namespace App\Controller;

use Core\component\AbstractController;
use App\Model\Repository\UserRepository;
use App\Service\EmailService;
use App\Service\EmailRenderer;
use App\Service\UserService;
use App\Service\ValidationService;

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
            $userRepository = new UserRepository();
            $userService = new UserService($userRepository);
            $validationService = new ValidationService();
            $emailRenderer = new EmailRenderer();
            $emailService = new EmailService($emailRenderer, $userService);

            if ($userService->userExists($userDatas['mail'])) {
                $this->addFlash('error', 'Cette adresse mail est déjà enregistrée.');
                return $this->redirect("/register");
            }
            $errorMessages = $validationService->validateRegistration($userDatas);
            if (!empty($errorMessages)) {
                $this->addFlash("error", $errorMessages);
                return $this->redirect("/register");
            }

            $user = $userService->createUser($userDatas);

            // Enregistrer l'utilisateur dans la base de données
            $userService->saveUser($user);
            $emailService->sendEmail($user,  'Confirmez votre inscription', $user->getRegistrationToken());

            $this->addFlash('success', 'Un email de confirmation vous a été envoyé.');
            return $this->redirect("/");
        }

        return $this->render("security/register.html.twig");
    }

    public function loginUser(){
    // { si oui login, message 
        // if ($this->isUserLoggedIn()){
            
        // }
        if ($this->isSubmitted("submit") && $this->isValided($_POST)) {
            $this->newSession();

            // Valider les données de connexion
            //  ajouter ici la logique de validation du formulaire de connexion

            // Exemple : Vérifier si l'utilisateur existe dans la base de données
            $userRepository = new UserRepository();
            $user = $userRepository->getUserBy('mail', $_POST["mail"]);
            if ($user && password_verify($_POST["password"], $user->getPassword())) {

                // Connexion réussie et nom prenom du user affiché
                $userFullName = $user->getFullName();
                $message = "Bienvenue, $userFullName !";
                $this->addFlash('success', $message);
                // Stock l'objet utilisateur dans la session
                $this->setSessionInfos("user", $user);
                // ajout dans la session des infos du user

                $this->setSessionInfos("userId", $user->getId());
                $this->setSessionInfos("mail", $user->getMail());
                $this->setSessionInfos("lastName", $user->getLastname());
                $this->setSessionInfos("role", $user->getRole());


                return $this->redirect("/");
            } else {
                //  message d'erreur en cas d'échec de connexion sans en mettre la raison par sécurité
                $this->addFlash("error", "identifiants invalides.");

                return $this->redirect("/login");
            }
        }
        return $this->render("security/login.html.twig");
    }


    public function confirmEmail(string $token)
    {
        $userService = new UserService(new UserRepository());

        if ($userService->confirmEmail($token)) {
            $this->addFlash('success', 'Votre adresse e-mail a été confirmée avec succès. Vous pouvez maintenant vous connecter.');
            return $this->redirect('/login');
        } else {
            $this->addFlash('error', 'Le lien de confirmation n\'est pas valide.');
            return $this->redirect("/");
        }
    }


    public function forgotPassword()
    {
        if ($this->isSubmitted("submit") && $this->isValided($_POST)) {

            // Récupérer l'utilisateur par son adresse e-mail
            $userEmail = $_POST['mail'];

            // Créez des objets de repository et de service
            $userRepository = new UserRepository();
            $userService = new UserService($userRepository);
            $emailRenderer = new EmailRenderer();
            $emailService = new EmailService($emailRenderer, $userService);

            // Recuperer l'utilisateur par son adresse e-mail
            $user = $userRepository->getUserBy('mail', $userEmail);
            if ($user) {
                // Générer le token de réinitialisation
                $resetToken = $userService->generateResetToken();

                // Enregistrer le jeton dans la base de données pour cet utilisateur
                $user->setResetToken($resetToken);
                $userRepository->updateResetToken($user);

                $emailService->sendResetEmail($user, 'Nouveau mot de passe', $user->getResetToken());

                $this->addFlash('success', 'Un e-mail de réinitialisation a été envoyé à votre adresse e-mail.');
            } else {
                $this->addFlash('error', 'Aucun utilisateur trouvé avec cette adresse e-mail.');
            }

            return $this->redirect('/forgotPassword');
        }
        return $this->render('security/forgotPassword.html.twig');
    }

    public function resetPassword($resetToken)
    {
        $userRepository = new UserRepository();
        $user = $userRepository->getUserBy('resetToken', $resetToken);
        if (!$user) {
            $this->addFlash('error', 'Le token de réinitialisation n\'est pas valide.');
            return $this->redirect('/forgotPassword');
        }

        if ($this->isSubmitted("submit") && $this->isValided($_POST)) {

            // Mettre à jour le mot de passe de l'utilisateur
            $newPassword = $_POST["new_password"];
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $user->setPassword($hashedPassword);

            // Effacer le token de réinitialisation
            $user->setResetToken(null);

            $userRepository->updatePassword($user->getMail(), $hashedPassword);

            $this->addFlash('success', 'Votre mot de passe a été réinitialisé avec succès. Vous pouvez maintenant vous connecter avec votre nouveau mot de passe.');
            return $this->redirect('/login');
        }

        return $this->render('security/newPassword.html.twig', ['resetToken' => $resetToken]);
    }
    public function logout()
    {
        if (isset($_SESSION['mail'])) {

            $this->deleteSession();

            $this->addFlash('success', 'Vous avez bien été déconnecté. À bientôt!');
        } else {
            $this->addFlash('error', 'Vous n\'êtes pas connecté.');
        }

        return $this->redirect('/login');
    }

    public function editProfil()
    {
        // 1. Récupérer l'utilisateur actuellement connecté
        $userId = $_SESSION['userId'];

        $userRepository = new UserRepository();
        $user = $userRepository->getUserBy('id', $userId);

        if (!$user) {
            // Gérer le cas où l'utilisateur n'est pas trouvé
            return $this->redirect('/');
        }
        // Valider et traiter les données du formulaire
        if ($this->isSubmitted("submit") && $this->isValidedProfil($_POST)) {
            if (
                $userRepository->getUserBy('mail', $_POST['mail'])
                && ($_POST['mail'] !== $user->getMail())
            ) {
                $this->addFlash('error', 'Cette adresse est déjà utilisée.');
                return $this->redirect('/editProfil');
            }
            $user->setLastname($_POST['lastname']);
            $user->setFirstname($_POST['firstname']);
            $user->setMail($_POST['mail']);

            $userService = new UserService($userRepository);
            $userService->updateUserPassword($user, $_POST['new_password']);
            // Vérifier si un nouveau mot de passe a été soumis
            $userRepository->editBddProfil($user);

            $this->addFlash('success', 'Vos données ont bien été modifiées.');
            // Rediriger l'utilisateur après la mise à jour
            return $this->redirect('/');
        } else if ($this->isSubmitted("submit") && !$this->isValidedProfil($_POST)) {
            // Gérer le cas où la validation échoue - la condition n'a pas l'air conditionné à $_POST
            $this->addFlash('error', 'Erreur de validation. Veuillez vérifier vos données.');
        }
        // Afficher le formulaire avec les valeurs actuelles
        return $this->render('security/editProfil.html.twig', ['user' => $user]);
    }
}
