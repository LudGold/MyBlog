<?php

namespace App\Service;

use Core\Config\ConfigMail;



class EmailCommentNotification extends ConfigMail
{
    private $emailRenderer;

    public function __construct(EmailRenderer $emailRenderer)
    {
        parent::__construct();
        $this->emailRenderer = $emailRenderer;
    }

    public function sendEmailComment($comment, $subject)
    {
        $htmlContent = $this->emailRenderer->renderCommentNotificationEmail();
        $this->sendGenericEmail($comment->getMail(), $subject, $htmlContent);
    }
}
