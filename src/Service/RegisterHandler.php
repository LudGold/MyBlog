<?php

namespace App\Service;

class RegisterHandler
{

    function checkFields($userDatas)
    {
        $error = [];

        if (!filter_var($userDatas["mail"], FILTER_VALIDATE_EMAIL)) {
            $error[] = ["email" => "veuillez saisir un email valide"];
        }
        if ($userDatas["lastname"] === "" || $userDatas["firstname"] === "") {
            $error[] = ["lastname" => "veuillez saisir vos nom et prénom"];
        }
        if ($userDatas["password"] !== $userDatas["checkpassword"]) {
            $error[] = ["password" => "votre mot de passe doit être identique"];
        }
        if (!preg_match("/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?;!@$%^&:*-]).{8,}$/", $userDatas["password"])) {
            $error[] = ["verifypassword" => "Le mot de passe doit contenir au moins 8 caractères, 1 chiffre et 1 caractère spécial."];
        }
        return $error;
    }
    
};
