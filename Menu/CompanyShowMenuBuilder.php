<?php

declare(strict_types=1);

namespace Owl\Bundle\AdminBundle\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Owl\Component\Company\Model\CompanyInterface;
use Owl\Component\Core\Model\ServiceInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

final class CompanyShowMenuBuilder
{
    public const EVENT_NAME = 'owl.menu.admin.company.show';

    /** @var FactoryInterface */
    private $factory;

    /** @var EventDispatcherInterface */
    private $eventDispatcher;

    public function __construct(
        FactoryInterface $factory,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->factory = $factory;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function createMenu(array $options): ItemInterface
    {
        $menu = $this->factory->createItem('root');

        if (!isset($options['company'])) {
            return $menu;
        }

        $company = $options['company'];
        $this->addChildren($menu, $company);

        return $menu;
    }

    private function addChildren(ItemInterface $menu, CompanyInterface $company): void
    {
        $menu->setExtra('column_id', 'actions');

        $menu
            ->addChild('update', [
                'route' => 'owl_admin_company_update',
                'routeParameters' => ['id' => $company->getId()],
            ])
            ->setAttribute('type', 'modal')
            ->setAttribute('icon', 'pencil')
            ->setLabel('owl.ui.edit')
        ;

        $menu
            ->addChild('user_delete', [
                'route' => 'owl_admin_company_delete',
                'routeParameters' => ['id' => $company->getId()],
            ])
            ->setAttribute('type', 'delete')
            ->setAttribute('resource_id', $company->getId())
            ->setLabel('owl.ui.delete')
        ;
    }
}
