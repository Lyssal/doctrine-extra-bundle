<?php

namespace Lyssal\DoctrineExtraBundle\Router;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * The entity router manager.
 */
class EntityRouterManager
{
    /**
     * @var EntityRouterInterface[] The entity routers
     */
    protected array $entityRouters = [];

    /**
     * Add entity routers.
     *
     * @param EntityRouterInterface[] $entityRouters The entity routers
     */
    public function addEntityRouters(\Traversable $entityRouters): void
    {
        foreach ($entityRouters as $entityRouter) {
            $this->addEntityRouter($entityRouter);
        }
    }

    /**
     * Add an entity router.
     */
    public function addEntityRouter(EntityRouterInterface $entityRouter): void
    {
        $this->entityRouters[] = $entityRouter;
    }

    /**
     * @see EntityRouterInterface::generate()
     */
    public function generate(object $entity, array $parameters = [], int $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH): ?string
    {
        foreach ($this->entityRouters as $entityRouter) {
            $url = $entityRouter->generate($entity, $parameters, $referenceType);

            if (null !== $url) {
                return $url;
            }
        }

        return null;
    }
}
