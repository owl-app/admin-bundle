<?php

declare(strict_types=1);

namespace Owl\Bundle\AdminBundle\Event;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Owl\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;

class EquipmentAddOnMenuEvent extends MenuBuilderEvent
{
    public function __construct(FactoryInterface $factory, ItemInterface $menu, private array $options)
    {
        parent::__construct($factory, $menu);
    }

    public function getOptions(): array
    {
        return $this->options;
    }
}
