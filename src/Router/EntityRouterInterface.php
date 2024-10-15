<?php

namespace Lyssal\DoctrineExtraBundle\Router;

use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

/**
 * The interface for the entity router handler.
 */
#[AutoconfigureTag('lyssal.entity_router')]
interface EntityRouterInterface
{
    /**
     * Generate the entity URL.
     *
     * @param object $entity        The routable entity
     * @param array  $parameters    The added route parameters
     * @param int    $referenceType The URL type
     *
     * @return string|null The URL
     */
    public function generate(object $entity, array $parameters, int $referenceType): ?string;
}
