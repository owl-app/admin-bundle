<?php

declare(strict_types=1);

namespace Owl\Bundle\AdminBundle\EventListener;

use Owl\Bundle\AdminBundle\Event\EquipmentAddOnMenuEvent;
use Owl\Component\Core\Equipment\Menu\EquipmentAddOnMenuListenerInteface;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;

class EquipmentRefuelingMenuListener implements EquipmentAddOnMenuListenerInteface
{
    public function __construct(private AuthorizationChecker $authorizationChecker)
    {
    }

    public function addTabs(EquipmentAddOnMenuEvent $event)
    {
        $menu = $event->getMenu();
        $options = $event->getOptions();

        if ($this->authorizationChecker->isGranted('owl_admin_equipment_show_refueling_index')) {
            $menu
                ->addChild('refueling_equipment')
                ->setExtra(
                    'route',
                    [
                        'path' => 'owl_admin_equipment_show_refueling_index',
                        'params' => [
                            'id' => $options['equipment']->getId(),
                        ],
                    ],
                )
                ->setLabel('owl.ui.refueling_equipment');
        }
    }

    public function addGridMenu(EquipmentAddOnMenuEvent $event)
    {
        $menu = $event->getMenu();
        $options = $event->getOptions();
        $isGrantedIndexRefueling = $this->authorizationChecker->isGranted('owl_admin_equipment_show_refueling_index');
        $isGrantedCreateRefueling = $this->authorizationChecker->isGranted('owl_admin_equipment_refueling_create');

        if ($isGrantedIndexRefueling || $isGrantedCreateRefueling) {
            $menuRefueling = $menu->addChild('equipment_refuelings')
                ->setLabel('owl.ui.manage_refueling')
                ->setExtra('icon', 'cubes');

            if ($isGrantedIndexRefueling) {
                $menuRefueling
                    ->addChild('list_refuelings')
                    ->setExtras([
                        'icon' => 'list',
                        'route' => [
                            'path' => 'owl_admin_equipment_show_refueling_index',
                            'params' => [
                                'id' => $options['id'],
                            ],
                        ],
                    ])
                    ->setLabel('owl.ui.list_refuelings');
            }

            if ($isGrantedCreateRefueling) {
                $menuRefueling
                    ->addChild('create_refueling')
                    ->setExtras([
                        'modal' => true,
                        'icon' => 'plus',
                        'route' => [
                            'path' => 'owl_admin_equipment_refueling_create',
                            'params' => [
                                'id' => $options['id'],
                            ],
                        ],
                    ])
                    ->setLabel('owl.ui.create_refueling');
            }
        }
    }
}
