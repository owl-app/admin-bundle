<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Owl\Bundle\AdminBundle\Context;

use Owl\Component\Core\Model\AdminUserInterface;
use Owl\Component\Locale\Context\LocaleContextInterface;
use Owl\Component\Locale\Context\LocaleNotFoundException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

final class AdminBasedLocaleContext implements LocaleContextInterface
{
    /** @var TokenStorageInterface */
    private $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public function getLocaleCode(): ?string
    {
        $token = $this->tokenStorage->getToken();
        if (null === $token) {
            throw new LocaleNotFoundException();
        }

        $user = $token->getUser();
        if (!$user instanceof AdminUserInterface) {
            throw new LocaleNotFoundException();
        }

        return $user->getLocaleCode();
    }
}
