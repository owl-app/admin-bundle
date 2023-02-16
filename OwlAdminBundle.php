<?php

namespace Owl\Bundle\AdminBundle;

use Owl\Bundle\AdminBundle\DependencyInjection\Compiler\ListenerEquipmentAddOnMenuPass;
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
    }
}
