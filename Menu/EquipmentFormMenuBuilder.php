<?php

declare(strict_types=1);

namespace Owl\Bundle\AdminBundle\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Owl\Component\Core\Model\EquipmentInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

final class EquipmentFormMenuBuilder
{
    /** @var FactoryInterface */
    private $factory;

    /** @var EventDispatcherInterface */
    private $eventDispatcher;

    public function __construct(FactoryInterface $factory, EventDispatcherInterface $eventDispatcher)
    {
        $this->factory = $factory;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function createMenu(array $options = []): ItemInterface
    {
        $menu = $this->factory->createItem('root');

        if (!array_key_exists('equipment', $options) || !$options['equipment'] instanceof EquipmentInterface) {
            return $menu;
        }

        $menu
            ->addChild('details')
            ->setAttribute('template', '@OwlAdmin/Equipment/Tab/_details.html.twig')
            ->setLabel('owl.ui.details')
            ->setCurrent(true)
        ;

        $menu
            ->addChild('attributes')
            ->setAttribute('template', '@OwlAdmin/Equipment/Tab/_attributes.html.twig')
            ->setLabel('owl.ui.attributes')
        ;

        $menu
            ->addChild('files')
            ->setAttribute('template', '@OwlAdmin/Equipment/Tab/_files.html.twig')
            ->setLabel('owl.ui.files')
        ;

        return $menu;
    }
}
