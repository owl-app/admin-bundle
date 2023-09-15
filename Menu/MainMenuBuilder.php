<?php

declare(strict_types=1);

namespace Owl\Bundle\AdminBundle\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Owl\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;

final class MainMenuBuilder
{
    public const EVENT_NAME = 'sylius.menu.admin.main';

    /** @var AuthorizationChecker */
    public $authorizationChecker;

    /** @var FactoryInterface */
    private $factory;

    /** @var EventDispatcherInterface */
    private $eventDispatcher;

    public function __construct(AuthorizationChecker $authorizationChecker, FactoryInterface $factory, EventDispatcherInterface $eventDispatcher)
    {
        $this->authorizationChecker = $authorizationChecker;
        $this->factory = $factory;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function createMenu(array $options): ItemInterface
    {
        $menu = $this->factory->createItem('root');

        $this->addCompanyMenu($menu);
        $this->addSuggestionMenu($menu);
        $this->addNotificationMenu($menu);
        $this->addCatalogSubMenu($menu);
        $this->addConfigurationSubMenu($menu);
        $this->addPermissionSubMenu($menu);

        $this->eventDispatcher->dispatch(new MenuBuilderEvent($this->factory, $menu), self::EVENT_NAME);

        return $menu;
    }

    private function addCatalogSubMenu(ItemInterface $menu): void
    {
        $isGrantedEquipments = $this->authorizationChecker->isGranted('owl_admin_equipment_index');
        $isGrantedEquipmentAttributes = $this->authorizationChecker->isGranted('owl_admin_equipment_attribute_index');

        if ($isGrantedEquipments || $isGrantedEquipmentAttributes) {
            $catalog = $menu
                ->addChild('catalog')
                ->setLabel('owl.menu.admin.main.catalog.header');

            if ($isGrantedEquipments) {
                $catalog
                    ->addChild('equipment', ['route' => 'owl_admin_equipment_index'])
                    ->setLabel('owl.menu.admin.main.equipment.header')
                    ->setLabelAttribute('icon', 'boxes');
            }

            if ($isGrantedEquipmentAttributes) {
                $catalog
                    ->addChild('equipment_attributes', ['route' => 'owl_admin_equipment_attribute_index'])
                    ->setLabel('owl.menu.admin.main.catalog.attributes')
                    ->setLabelAttribute('icon', 'cubes');
            }
        }
    }

    private function addCompanyMenu(ItemInterface $menu): void
    {
        $isGranted = $this->authorizationChecker->isGranted('owl_admin_company_index');

        if ($isGranted) {
            $menu
                ->addChild('company', ['route' => 'owl_admin_company_index'])
                ->setLabel('owl.menu.admin.main.company.header')
                ->setLabelAttribute('icon', 'building outline');
        }
    }

    public function addNotificationMenu(ItemInterface $menu): void
    {
        $isGranted = $this->authorizationChecker->isGranted('owl_admin_notification_index');

        if ($isGranted) {
            $menu
                ->addChild('notification', ['route' => 'owl_admin_notification_index'])
                ->setLabel('owl.menu.admin.main.notification.header')
                ->setLabelAttribute('icon', 'bullhorn');
        }
    }

    private function addSuggestionMenu(ItemInterface $menu): void
    {
        $isGranted = $this->authorizationChecker->isGranted('owl_admin_suggestion_index');

        if ($isGranted) {
            $menu
                ->addChild('suggestion', ['route' => 'owl_admin_suggestion_index'])
                ->setLabel('owl.menu.admin.main.suggestion.header')
                ->setLabelAttribute('icon', 'question');
        }
    }

    private function addConfigurationSubMenu(ItemInterface $menu): void
    {
        $isGrantedLocales = $this->authorizationChecker->isGranted('owl_admin_locale_index');
        $isGrantedUsers = $this->authorizationChecker->isGranted('owl_admin_admin_user_index');
        $isGrantedSettings = $this->authorizationChecker->isGranted('owl_admin_setting_index');

        if ($isGrantedLocales || $isGrantedUsers || $isGrantedSettings) {
            $configuration = $menu
                ->addChild('configuration')
                ->setLabel('owl.menu.admin.main.configuration.header')
            ;

            if ($isGrantedLocales) {
                $configuration
                    ->addChild('admin_locales', ['route' => 'owl_admin_locale_index'])
                    ->setLabel('owl.menu.admin.main.configuration.locales')
                    ->setLabelAttribute('icon', 'flag');
            }

            if ($isGrantedUsers) {
                $configuration
                    ->addChild('admin_users', ['route' => 'owl_admin_admin_user_index'])
                    ->setLabel('owl.menu.admin.main.configuration.users')
                    ->setLabelAttribute('icon', 'users');
            }

            if ($isGrantedSettings) {
                $configuration
                    ->addChild('admin_settings', ['route' => 'owl_admin_setting_index'])
                    ->setLabel('owl.menu.admin.main.configuration.settings')
                    ->setLabelAttribute('icon', 'settings');
            }
        }
    }

    private function addPermissionSubMenu(ItemInterface $menu): void
    {
        $isGrantedPermissionRoles = $this->authorizationChecker->isGranted('owl_admin_rbac_role_index');
        $isGrantedPermissionAvailableRoutes = $this->authorizationChecker->isGranted('owl_admin_rbac_permission_availables');

        if ($isGrantedPermissionRoles || $isGrantedPermissionAvailableRoutes) {
            $configuration = $menu
                ->addChild('permission')
                ->setLabel('owl.menu.admin.main.permission.header')
            ;

            if ($isGrantedPermissionRoles) {
                $configuration
                    ->addChild('admin_permission_roles', ['route' => 'owl_admin_rbac_role_index'])
                    ->setLabel('owl.menu.admin.main.permission.roles')
                    ->setLabelAttribute('icon', 'user secret')
                    ->setExtra('is_granted', $isGrantedPermissionRoles);
            }

            if ($isGrantedPermissionAvailableRoutes) {
                $configuration
                    ->addChild('admin_permission_available_routes', ['route' => 'owl_admin_rbac_permission_availables'])
                    ->setLabel('owl.menu.admin.main.permission.available')
                    ->setLabelAttribute('icon', 'lock')
                    ->setExtra('is_granted', $isGrantedPermissionAvailableRoutes);
            }
        }
    }
}
