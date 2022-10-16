<?php

declare(strict_types=1);

namespace Owl\Bundle\AdminBundle\Provider;

use Owl\Component\Core\Context\AdminUserContextInterface;
use Owl\Component\Core\Repository\NotificationRepositoryInterface;

class AdminTwigGlobalDataProvider implements AdminTwigGlobalDataProviderInterface
{
    private ?array $notifications = null;

    public function __construct(
        private NotificationRepositoryInterface $notificationRepository,
        private AdminUserContextInterface $adminUserContext
    ) {
    }

    public function getNotifications(): array
    {
        $roleCanonical = $this->adminUserContext->getRoleCanonicalName();
        $user = $this->adminUserContext->getUser();
        if (is_null($this->notifications)) {
            $this->notifications = $this->notificationRepository->findAllNotAccepted(
                $this->adminUserContext->getUser(),
                $this->adminUserContext->getRoleCanonicalName()
            );
        }

        return $this->notifications;
    }
}
