<?php


namespace SY\PlatformBundle\Email;


use SY\PlatformBundle\Entity\Application;

class ApplicationMailer
{
    private $mailer;

    public function __construct(\Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendNewNotification(Application $application)
    {
        $message = new \Swift_Message(
            'Nouvelle candidature',
            'Vous avrez reçu une nouvelle candidature');

        $message
            ->addTo($application->getAdvert()->getEmail())
            ->addFrom('admin@monsite.com');

        $this->mailer->send($message);
    }
}