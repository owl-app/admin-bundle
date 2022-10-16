<?php

declare(strict_types=1);

namespace Owl\Bundle\AdminBundle\DependencyInjection\Compiler;

use Sylius\Component\Resource\Metadata\Metadata;
use Sylius\Component\Resource\Metadata\MetadataInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\ContainerInterface;

final class ListResourceWithParentPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $resourceRegistry = $container->get('sylius.resource_registry');
 
        foreach ($container->findTaggedServiceIds('owl.controller.action.list_with_parent_resource') as $id => $attributes) {
            $listWithParentDefinition = $container->findDefinition($id);

            foreach($attributes as $attribute) {
                if(is_null($attribute['parent']) || is_null($attribute['grid'])) {
                    throw new InvalidArgumentException('Tagged list with parent resource actions needs to have "parent" and "grid" attribute.');
                }

                $metadataParent = $resourceRegistry->get($attribute['parent']);
                $metadataChild = $resourceRegistry->get($attribute['grid']);

                $definition = new Definition($listWithParentDefinition->getClass());
                $definition
                    ->setPublic(true)
                    ->setArguments([
                        $this->getMetadataDefinition($metadataParent),
                        $this->getMetadataDefinition($metadataChild),
                        new Reference('sylius.resource_controller.resources_collection_provider'),
                        new Reference($metadataParent->getServiceId('repository')),
                        new Reference('sylius.resource_controller.request_configuration_factory'),
                        new Reference('sylius.resource_controller.view_handler', ContainerInterface::NULL_ON_INVALID_REFERENCE),
                    ])
                    ->addTag('owl.controller.action', ['alias' => $attribute['grid']])
                ;

                $container->setDefinition(implode('.', [$id, $metadataParent->getName(), $metadataChild->getName()]), $definition);
            }
        }

    }

    protected function getMetadataDefinition(MetadataInterface $metadata): Definition
    { 
        $definition = new Definition(Metadata::class);
        $definition
            ->setFactory([new Reference('sylius.resource_registry'), 'get'])
            ->setArguments([$metadata->getAlias()])
        ;

        return $definition;
    }
}
