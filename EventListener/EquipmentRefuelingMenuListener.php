<?php

declare(strict_types=1);

namespace Owl\Bundle\AdminBundle\EventListener;

use Owl\Bundle\AdminBundle\Event\EquipmentAddOnMenuEvent;
use Owl\Component\Core\Equipment\Menu\EquipmentAddOnMenuListenerInteface;

class EquipmentRefuelingMenuListener implements EquipmentAddOnMenuListenerInteface
{
    public function addTabs(EquipmentAddOnMenuEvent $event)
    {
        $menu = $event->getMenu();
        $options = $event->getOptions();

        $menu
            ->addChild('refueling_equipment')
            ->setAttribute('route', [
                'path' => 'owl_admin_equipment_show_refueling_index',
                'params' => [
                    'id' => $options['equipment']->getId()
                ]
            ])
            ->setLabel('owl.ui.refueling_equipment')
        ;
    }

    public function addGridMenu(EquipmentAddOnMenuEvent $event)
    {
        $menu = $event->getMenu();
        $options = $event->getOptions();

        $menuRefueling = $menu->addChild('equipment_refuelings')
            ->setLabel('owl.ui.manage_refueling')
            ->setExtra('icon', 'cubes')
        ;

        $menuRefueling
            ->addChild('list_refuelings')
            ->setExtra('icon', 'list')
            ->setAttribute('route', [
                'path' => 'owl_admin_equipment_show_refueling_index',
                'params' => [
                    'id' => $options['id']
                ]
            ])

            ->setLabel('owl.ui.list_refuelings')
        ;

        $menuRefueling
            ->addChild('create_refueling')
            ->setExtra('icon', 'plus')
            ->setExtra('modal', true)
            ->setAttribute('route', [
                'path' => 'owl_admin_equipment_refueling_create',
                'params' => [
                    'id' => $options['id']
                ]
            ])

            ->setLabel('owl.ui.create_refueling')
        ;
    }
}
