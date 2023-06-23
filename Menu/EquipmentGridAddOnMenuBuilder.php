<?php

declare(strict_types=1);

namespace Owl\Bundle\AdminBundle\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Owl\Bundle\AdminBundle\Event\EquipmentAddOnMenuEvent;
use Owl\Bundle\AdminBundle\Event\OrderMenuEvent;
use Owl\Component\Core\Model\EquipmentCategoryInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

final class EquipmentGridAddOnMenuBuilder
{
    public const EVENT_ADDON_ALL_CATEGORIES = 'owl.admin.menu.grid.equipment_addon.all';

    public function __construct(
        private FactoryInterface $factory,
        private EventDispatcherInterface $eventDispatcher,
        private array $addOnEvents
    ) {

    }

    public function createMenu(array $options = []): ItemInterface
    {
        $menu = $this->factory->createItem('root');

        if (!array_key_exists('id', $options) || !$options['category'] instanceof EquipmentCategoryInterface) {
            return $menu;
        }

        /** @var EquipmentCategoryInterface $category */
        $category = $options['category'];

        foreach ($category->getAddons() as $addon) {
            $this->eventDispatcher->dispatch(
                new EquipmentAddOnMenuEvent($this->factory, $menu, $options),
                $this->addOnEvents[$addon]['grid']
            );

            if ($options['order']) {
                $this->eventDispatcher->dispatch(
                    new OrderMenuEvent($this->factory, $menu, $options['order']),
                    OrderMenuEvent::EVENT_NAME
                );
            }
        }

        return $menu;
    }
}
