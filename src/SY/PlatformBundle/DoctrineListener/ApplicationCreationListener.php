<?php


namespace SY\PlatformBundle\DoctrineListener;


use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use SY\PlatformBundle\Email\ApplicationMailer;
use SY\PlatformBundle\Entity\Application;

class ApplicationCreationListener
{
    private $applicationMailer;

    public function __construct(ApplicationMailer $applicationMailer)
    {
        $this->applicationMailer = $applicationMailer;
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if (!$entity instanceof Application) {
            return;
        }

        $this->applicationMailer->sendNewNotification($entity);
    }
}