<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class MailController extends AbstractController
{
    #[Route(path: '/mail', name: 'app_mail')]
    public function Email(MailerInterface $mailer)
    {
        $email = (new Email())
            ->from('hello@example.com')
            ->to('basia.zygilewicz@gmail.com')
            ->subject('Verify your email on Cauldron Overflow!')
            ->text('Please, follow the link to verify your email!');
        $mailer->send($email);

        return $this->render('security/main.html.twig');
    }
}
