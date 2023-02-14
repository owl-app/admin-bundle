<?php

declare(strict_types=1);

namespace Owl\Bundle\AdminBundle\Controller\Action;

use FOS\RestBundle\View\View;
use Owl\Bridge\SyliusResource\Controller\AbstractResourceAction;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfigurationFactoryInterface;
use Sylius\Bundle\ResourceBundle\Controller\ResourcesCollectionProviderInterface;
use Sylius\Bundle\ResourceBundle\Controller\ViewHandlerInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Metadata\MetadataInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Sylius\Component\Resource\ResourceActions;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class ListResourceWithParentAction extends AbstractResourceAction
{
    public function __construct(
        private MetadataInterface $parentMetadata,
        private MetadataInterface $gridMetadata,
        private ResourcesCollectionProviderInterface $resourcesCollectionProvider,
        private RepositoryInterface $repositoryCompany,
        private RequestConfigurationFactoryInterface $requestConfigurationFactory,
        private ViewHandlerInterface $viewHandler
    ) {
    }

    public function __invoke(Request $request): Response
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);
        $company = $this->findCompanyOr404($request);

        $this->isGrantedOr403($configuration, $company);
        $resources = $this->resourcesCollectionProvider->get($configuration, $this->repository);

        $this->eventDispatcher->dispatchMultiple(ResourceActions::INDEX, $configuration, $resources);

        return $this->render($configuration->getTemplate(ResourceActions::INDEX . '.html'), [
            $this->parentMetadata->getName() => $company,
            'configuration' => $configuration,
            'metadata' => $this->metadata,
            'resources' => $resources,
            $this->gridMetadata->getPluralName() => $resources,
        ]);
    }

    protected function findCompanyOr404(Request $request): ResourceInterface
    {
        $company = $this->repositoryCompany->find(
            (int) $request->attributes->get('id'),
        );

        if (!$request->attributes->has('id') || null === $company) {
            throw new NotFoundHttpException(sprintf('The company has not been found'));
        }

        return $company;
    }
}
