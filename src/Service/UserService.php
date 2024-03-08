<?php

namespace App\Service;

use App\Model\Entity\User;
use App\Model\Repository\UserRepository;

class UserService
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    private function generateToken()
    {
        // Générez votre token de manière sécurisée
        $token = bin2hex(random_bytes(32));
        return $token;
    }
    public function generateResetToken(): string
    {

        $resetToken = bin2hex(random_bytes(32));

        return $resetToken;
    }

    public function userExists(string $email)
    {
        return $this->userRepository->getUserBy('mail', $email) !== null;
    }

    public function createUser(array $userDatas)
    {
        $user = new User($userDatas);

        $registrationToken = $this->generateToken();
        $user->setRegistrationToken($registrationToken);
        return $user;
    }

    public function saveUser(User $user): void
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


    public function updateUserRole(int $userId, array $newRole): bool
    {
        $newRole = (array) $newRole;
        $user = $this->userRepository->getUserBy('id', $userId);
        if ($user) {
            $user->setRole($newRole);
            $this->userRepository->updateUserRole($userId, $newRole);
            return true;
        }
        return false;
    }

    public function updateUserPassword(User $user, string $newPassword): void
    {
        // Vérifier si un nouveau mot de passe a été soumis
        if (!empty($newPassword)) {
            // Hashe le nouveau mot de passe
            $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
            // Met à jour le mot de passe de l'utilisateur
            $user->setPassword($hashedPassword);
        }
    }
}
