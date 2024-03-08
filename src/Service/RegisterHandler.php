<?php

namespace App\Service;

class RegisterHandler
{

    public function checkFields(array $userDatas)
    {
        $error = "";
        if (!filter_var($userDatas["mail"], FILTER_VALIDATE_EMAIL)) {
            $error =  "veuillez saisir un email valide";
        } elseif ($userDatas["lastname"] === "" || $userDatas["firstname"] === "") {
            $error = "veuillez saisir vos nom et prénom";
        } elseif ($userDatas["password"] !== $userDatas["checkpassword"]) {
            $error =  "votre mot de passe doit être identique";
        } elseif (!preg_match("/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?;!@$%^&:*-]).{8,}$/", $userDatas["password"])) {
            $error = "Le mot de passe doit contenir au moins 8 caractères, 1 chiffre et 1 caractère spécial.";
        }
        return $error;
    }
};
