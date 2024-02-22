<?php

namespace App\Service;

use App\Model\Entity\User;


class UserService
{
    private $userRepository;

    public function __construct($userRepository)
    {
        $this->userRepository = $userRepository;
    }
    private function generateToken()
    {
        // Générez votre token de manière sécurisée ici (peut-être en utilisant une librairie dédiée)
        $token = bin2hex(random_bytes(32));

        return $token;
    }
    public function generateResetToken(): string
    {

        $resetToken = bin2hex(random_bytes(32));

        return $resetToken;
    }

    public function userExists($email)
    {
        return $this->userRepository->getUserBy('mail', $email) !== null;
    }

    public function createUser($userDatas)
    {
        $user = new User($userDatas);
        // Vous pourriez ici générer le token, hacher le mot de passe, etc
        // Générer et définir le token de confirmation
        $registrationToken = $this->generateToken();
        $user->setRegistrationToken($registrationToken);
        return $user;
    }

    public function saveUser(User $user)
    {
        $this->userRepository->saveUser($user);
    }
    public function confirmEmail(string $token): bool
    {
        $user = $this->userRepository->getUserBy('registrationToken', $token);

        if ($user) {
            $user->setIsConfirmed(true);
            $this->userRepository->saveUser($user);
            return true;
        }

        return false;
    }


    public function updateUserRole($userId, $newRole)
    {
        $user = $this->userRepository->getUserBy('id', $userId);
        
        if ($user) {
            $user->setRole($newRole);
            $this->userRepository->updateUserRole($userId, $newRole);
            return true;
        }
        return false;
    }

    public function updateUserPassword($user, $newPassword)
    {
        // Vérifier si un nouveau mot de passe a été soumis
        if (!empty($newPassword)) {
            // Hasher le nouveau mot de passe
            $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
            // Mettre à jour le mot de passe de l'utilisateur
            $user->setPassword($hashedPassword);
        }
    }
}


