<?php

declare(strict_types=1);

namespace Owl\Bundle\AdminBundle\EventListener;

use Owl\Bundle\AdminBundle\Event\EquipmentAddOnMenuEvent;
use Owl\Component\Core\Equipment\Menu\EquipmentAddOnMenuListenerInteface;

class EquipmentEventMenuListener implements EquipmentAddOnMenuListenerInteface
{
    public function addTabs(EquipmentAddOnMenuEvent $event)
    {
        $menu = $event->getMenu();
        $options = $event->getOptions();

        $menu
            ->addChild('event_equipment')
            ->setAttribute('route', [
                'path' => 'owl_admin_equipment_show_event_index',
                'params' => [
                    'id' => $options['equipment']->getId(),
                ],
            ])
            ->setLabel('owl.ui.equipment_events');
    }

    public function addGridMenu(EquipmentAddOnMenuEvent $event)
    {
        $menu = $event->getMenu();
        $options = $event->getOptions();

        $menuEvent = $menu->addChild('equipment_events')
            ->setLabel('owl.ui.manage_events')
            ->setExtra('icon', 'cubes')
        ;

        $menuEvent
            ->addChild('list_events')
            ->setExtra('icon', 'list')
            ->setAttribute('route', [
                'path' => 'owl_admin_equipment_show_event_index',
                'params' => [
                    'id' => $options['id'],
                ],
            ])

            ->setLabel('owl.ui.list_events')
        ;

        $menuEvent
            ->addChild('create_event')
            ->setExtra('icon', 'plus')
            ->setExtra('modal', true)
            ->setAttribute('route', [
                'path' => 'owl_admin_equipment_event_create',
                'params' => [
                    'id' => $options['id'],
                ],
            ])

            ->setLabel('owl.ui.create_event')
        ;
    }
}
