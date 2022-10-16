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

namespace Owl\Bundle\AdminBundle\Event;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Owl\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;
use Owl\Component\Core\Model\ServiceInterface;

class ServiceShowMenuBuilderEvent extends MenuBuilderEvent
{
    /** @var ServiceInterface */
    private $service;

    public function __construct(FactoryInterface $factory, ItemInterface $menu, ServiceInterface $service)
    {
        parent::__construct($factory, $menu);

        $this->service = $service;
    }

    public function getService(): ServiceInterface
    {
        return $this->service;
    }
}
