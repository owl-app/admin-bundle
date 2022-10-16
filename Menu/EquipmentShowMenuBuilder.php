<?php

declare(strict_types=1);

namespace Owl\Bundle\AdminBundle\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Owl\Bundle\AdminBundle\Event\EquipmentAddOnMenuEvent;
use Owl\Bundle\AdminBundle\Event\OrderMenuEvent;
use Owl\Component\Core\Model\EquipmentInterface;
use Owl\Component\Core\Provider\EquipmentCategoryCodeProviderInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

final class EquipmentShowMenuBuilder
{
    public const EVENT_ADDON_ALL_CATEGORIES = 'owl.admin.menu.show.equipment_addon.all';

    public function __construct(
        private FactoryInterface $factory,
        private EventDispatcherInterface $eventDispatcher,
        private EquipmentCategoryCodeProviderInterface $equipmentCategoryCodeProvider,
        private array $addOnEvents
    ) {
    }

    public function createMenu(array $options = []): ItemInterface
    {
        $menu = $this->factory->createItem('root');

        if (!array_key_exists('equipment', $options) || !$options['equipment'] instanceof EquipmentInterface) {
            return $menu;
        }

        $category = $options['equipment']->getCategory();
        $categoryCode = $category ? $this->equipmentCategoryCodeProvider->getCode($category->getId()) : null;

        $menu
            ->addChild('details')
            ->setAttribute('route', [
                'path' => 'owl_admin_equipment_show_details',
                'params' => [
                    'id' => $options['equipment']->getId()
                ]
            ])

            ->setLabel('owl.ui.details');

        $menu
            ->addChild('history_equipment')
            ->setAttribute('route', [
                'path' => 'owl_admin_equipment_show_history_index',
                'params' => [
                    'id' => $options['equipment']->getId()
                ]
            ])
            ->setLabel('owl.ui.history_equipment');

        $this->eventDispatcher->dispatch(
            new EquipmentAddOnMenuEvent($this->factory, $menu, $options),
            self::EVENT_ADDON_ALL_CATEGORIES
        );

        if ($category && $categoryCode) {
            $this->eventDispatcher->dispatch(
                new EquipmentAddOnMenuEvent($this->factory, $menu, $options),
                $this->addOnEvents[$categoryCode]['show']
            );
        }

        if ($options['order_menu']) {
            $this->eventDispatcher->dispatch(
                new OrderMenuEvent($this->factory, $menu, $options['order_menu']),
                OrderMenuEvent::EVENT_NAME
            );
        }

        return $menu;
    }
}
