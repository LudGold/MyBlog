<?php

namespace App\Service;

class ValidationService {

        public function validateRegistration($userDatas) {
            $errors = [];
    
            // Vérification des champs requis
            $requiredFields = ['lastname', 'firstname', 'mail', 'password', 'checkpassword'];
            foreach ($requiredFields as $field) {
                if (empty($userDatas[$field])) {
                    $errors[] = "Le champ $field est requis.";
                }
            }
            // Vérification de la validité de l'adresse e-mail
            if (!filter_var($userDatas['mail'], FILTER_VALIDATE_EMAIL)) {
                $errors[] = "L'adresse e-mail n'est pas valide.";
            }
    
            // Vérification de la correspondance des mots de passe
            if ($userDatas['password'] !== $userDatas['checkpassword']) {
                $errors[] = "Les mots de passe ne correspondent pas.";
            }
    
            
            return $errors;
        }
    }
