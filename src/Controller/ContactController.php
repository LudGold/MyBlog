<?php

namespace App\Controller;

use Core\component\AbstractController;
use App\Service\EmailRenderer;
use App\Service\EmailService;

class ContactController extends AbstractController
{

    public function contact()
    {
        if ($this->isSubmitted('submit') && $this->isValided($_POST)) {

            if (!empty($_POST['spam'])) {
                // Si le champ anti-spam rempli, il peut s'agir d'un bot
                $this->addFlash('error', 'Votre message a été identifié comme spam, veuillez réessayer.');
                return $this->render('contact/contact.html.twig');
            }
            $name = $_POST['name'];
            $email = $_POST['email'];
            $message = $_POST['message'];
            // Vérifiez si la checkbox de consentement est cochée
            if (isset($_POST['consent'])) {

                $emailRenderer = new EmailRenderer();
                $emailContent = $emailRenderer->renderContactEmail($name, $email, $message);

                $emailService = new EmailService($emailRenderer);
                $emailService->sendContactEmail($emailContent);

                $this->addFlash('success', 'Votre demande de contact a bien été envoyée, je vous répondrai dans les meilleurs délais.');

                return $this->redirect('/');
            } else {

                $this->addFlash('error', 'Veuillez accepter les mentions légales avant de continuer.');
            }
        }
        return $this->render('home/home.html.twig');
    }
}
