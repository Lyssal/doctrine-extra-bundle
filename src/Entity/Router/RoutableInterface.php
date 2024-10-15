<?php

namespace Lyssal\DoctrineExtraBundle\Entity\Router;

/**
 * The routable interface.
 */
interface RoutableInterface
{
    /**
     * Get the route properties (route name and parameters).
     *
     * @return array|string The route properties (or only name if any parameters)
     */
    public function getRouteProperties(): array|string;
}
