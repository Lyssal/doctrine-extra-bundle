<?php

namespace Lyssal\DoctrineExtraBundle\Decorator;

use Lyssal\DoctrineExtraBundle\Entity\Property\PropertyGetter;

/**
 * The abstract decorator handler.
 */
abstract class AbstractDecorator implements DecoratorInterface
{
    /**
     * @var object The entity
     */
    protected object $entity;

    public function __construct(protected readonly DecoratorManager $decoratorManager)
    {
    }

    /**
     * @see DecoratorInterface::setEntity()
     */
    public function setEntity(object $entity): void
    {
        $this->entity = $entity;
    }

    /**
     * @see DecoratorInterface::getEntity()
     */
    public function getEntity(): object
    {
        return $this->entity;
    }

    /**
     * Process the getters ans the setters.
     *
     * @param string $name The function or property name
     * @param array  $args The method arguments
     */
    public function __call(string $name, array $args): mixed
    {
        $value = (new PropertyGetter($this->entity))->get($name, $args);

        if (\is_object($value) && !$value instanceof DecoratorInterface && $this->decoratorManager->isSupportedEntity($value)) {
            return $this->decoratorManager->get($value);
        }

        return $value;
    }

    /**
     * Get the entity string.
     */
    public function __toString(): string
    {
        return (string) $this->entity;
    }
}
