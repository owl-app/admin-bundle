<?php

declare(strict_types=1);

namespace Owl\Bundle\AdminBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class ListenerEquipmentAddOnMenuPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $addOnMenuEvents = [];

        foreach ($container->findTaggedServiceIds('owl.equipment.listener.addon_menu') as $id => $attributes) {
            $attribute = $attributes[0];
            if (!isset($attribute['category_code'])) {
                throw new \InvalidArgumentException('Tagged attribute type needs to have `category_code` attributes.');
            }

            $showEvent = 'owl.admin.menu.show.equipment_addon.'.$attribute['category_code'];
            $gridEvent = 'owl.admin.menu.grid.equipment_addon.'.$attribute['category_code'];

            $listener = $container->getDefinition($id);
            $listener->clearTags();
            $listener->addTag('kernel.event_listener', [
                'event' => $showEvent,
                'method' => 'addTabs',
                'priority' => $attribute['priority'] ?? 0
            ]);

            $listener->addTag('kernel.event_listener', [
                'event' => $gridEvent,
                'method' => 'addGridMenu',
                'priority' => $attribute['priority'] ?? 0
            ]);

            $addOnMenuEvents[$attribute['category_code']] = [
                'show' => $showEvent,
                'grid' => $gridEvent,
            ];
        }

        $container->setParameter('owl.admin.menu.equipment_addon.events', $addOnMenuEvents);
    }
}
