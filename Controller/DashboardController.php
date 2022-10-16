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

namespace Owl\Bundle\AdminBundle\Controller;

use Owl\Component\Setting\Storage\SettingStorageInterface;
use Sylius\Bundle\AdminBundle\Provider\StatisticsDataProviderInterface;
use Sylius\Component\Channel\Repository\ChannelRepositoryInterface;
use Sylius\Component\Core\Dashboard\SalesDataProviderInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment;

final class DashboardController
{
    /**
     * @param EngineInterface|Environment $templatingEngine
     */
    public function __construct(
        private SettingStorageInterface $settingStorage,
        private object $templatingEngine,
        private RouterInterface $router
    ) {
    }

    public function indexAction(Request $request): Response
    {
        $settings = $this->settingStorage->getBySectionAndKeys('system', ['description_dashboard']);

        return new Response($this->templatingEngine->render('@OwlAdmin/Dashboard/index.html.twig', [
            'settings' => $settings
        ]));
    }
}
