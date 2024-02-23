<?php

namespace App\Service;

use Core\Config\ConfigMail;
use App\Model\Entity\User;
use App\Service\EmailRenderer;
use App\Service\UserService;

class EmailService  extends ConfigMail

{
    public $emailRenderer;
    private $userService;

    public function __construct(EmailRenderer $emailRenderer, UserService $userService = null)
    {
        parent::__construct();
        $this->emailRenderer = $emailRenderer;
        $this->userService = $userService;
    }

    public function sendEmail(User $user, $subject, $token)
    {

        $htmlContent = $this->emailRenderer->renderConfirmationEmail($token);
        $this->sendGenericEmail($user->getMail(), $subject, $htmlContent);
    }

    public function sendResetEmail($user, $subject, $resetToken)
    {

        $htmlContent = $this->emailRenderer->renderResetPasswordEmail($resetToken);
        $this->sendGenericEmail($user->getMail(), $subject, $htmlContent);
    }
    public function sendContactEmail($emailContent)
    {
        $subject = 'Nouveau message de contact';

        $this->sendGenericEmail($this->getAdminEmail(), $subject, $emailContent);
    }
    public function getAdminEmail()
    {
        return 'ludivinezarkos@gmail.com';
    }
}
