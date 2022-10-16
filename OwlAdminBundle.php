<?php

namespace Owl\Bundle\AdminBundle;

use Owl\Bundle\AdminBundle\DependencyInjection\Compiler\ListenerEquipmentAddOnMenuPass;
use Owl\Bundle\AdminBundle\DependencyInjection\Compiler\ListResourceWithParentPass;
use Owl\Bundle\AdminBundle\DependencyInjection\Compiler\RegisterEquipmentAddOnMenuPass;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class OwlAdminBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new ListenerEquipmentAddOnMenuPass());
        $container->addCompilerPass(new ListResourceWithParentPass(), PassConfig::TYPE_BEFORE_OPTIMIZATION, -1);
    }
}