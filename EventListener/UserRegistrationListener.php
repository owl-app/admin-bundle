<?php

declare(strict_types=1);

namespace Owl\Bundle\AdminBundle\EventListener;

use Owl\Component\Core\Model\AdminUserInterface;
use Owl\Bundle\UserBundle\UserEvents;
use Owl\Component\User\Security\Generator\GeneratorInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Webmozart\Assert\Assert;

final class UserRegistrationListener
{
    public function __construct(
        private GeneratorInterface $tokenGenerator,
        private EventDispatcherInterface $eventDispatcher,
    ) {
    }

    public function handleUserVerification(GenericEvent $event): void
    {
        $user = $event->getSubject();
        Assert::isInstanceOf($user, AdminUserInterface::class);

        $this->sendVerificationEmail($user);
    }

    private function sendVerificationEmail(AdminUserInterface $user): void
    {
        $this->eventDispatcher->dispatch(new GenericEvent($user), UserEvents::REQUEST_VERIFICATION_TOKEN);
    }
}
