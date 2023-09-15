<?php

declare(strict_types=1);

namespace Owl\Bundle\AdminBundle;

use Owl\Bundle\AdminBundle\DependencyInjection\Compiler\ListenerEquipmentAddOnMenuPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class OwlAdminBundle extends Bundle
{
    /**
     * @inheritdoc
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new ListenerEquipmentAddOnMenuPass());
    }
}
