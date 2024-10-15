<?php

namespace Lyssal\DoctrineExtraBundle\Decorator;

use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

/**
 * The interface for the decorator handler.
 */
#[AutoconfigureTag('lyssal.decorator')]
interface DecoratorInterface
{
    /**
     * Return if the entity is supported by the decorator manager.
     *
     * @param object $entity The decorator's entity
     *
     * @return bool If the entity is supported
     */
    public function supports(object $entity): bool;

    /**
     * Set the entity of the decorator.
     *
     * @param object $entity The entity
     */
    public function setEntity(object $entity): void;

    /**
     * Get the entity of the decorator.
     *
     * @return object The entity
     */
    public function getEntity(): object;
}
