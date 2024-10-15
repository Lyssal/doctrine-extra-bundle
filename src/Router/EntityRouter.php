<?php

/**
 * Ce fichier fait partie d'un projet Lyssal.
 *
 * This file is part of a Lyssal project.
 *
 * @copyright Rémi Leclerc
 * @author Rémi Leclerc
 */

namespace Lyssal\DoctrineExtraBundle\Router;

use Lyssal\DoctrineExtraBundle\Decorator\DecoratorInterface;
use Lyssal\DoctrineExtraBundle\Entity\Router\RoutableInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class EntityRouter implements EntityRouterInterface
{
    public function __construct(protected readonly RouterInterface $router)
    {
    }

    /**
     * @see \Lyssal\DoctrineExtraBundle\Router\EntityRouterInterface::generate()
     *
     * @throws \InvalidArgumentException If the getRouteProperties() method returns bad arguments
     */
    public function generate(object $entity, array $parameters = [], int $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH): ?string
    {
        if (!$entity instanceof RoutableInterface) {
            if ($entity instanceof DecoratorInterface) {
                return $this->generate($entity->getEntity(), $parameters, $referenceType);
            }

            return null;
        }

        $routeProperties = $entity->getRouteProperties();
        $routeParameters = [];

        if (\is_string($routeProperties)) {
            $route = $routeProperties;
        } elseif (\is_array($routeProperties) && count($routeProperties) > 0) {
            $route = $routeProperties[0];

            if (\count($routeProperties) > 1) {
                if (!\is_array($routeProperties[1])) {
                    throw new \InvalidArgumentException('The second cell of the returned array in `getRouteProperties()` has to be an array with the route parameters.');
                }

                $routeParameters = $routeProperties[1];
            }
        } else {
            throw new \InvalidArgumentException('The `getRouteProperties()` method must returned at least the route name.');
        }

        $routeParameters = [...$routeParameters, ...$parameters];

        return $this->router->generate($route, $routeParameters, $referenceType);
    }
}
