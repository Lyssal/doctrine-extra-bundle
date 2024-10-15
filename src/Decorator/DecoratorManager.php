<?php

namespace Lyssal\DoctrineExtraBundle\Decorator;

use Lyssal\DoctrineExtraBundle\Exception\DecoratorException;

/**
 * the decorators' manager.
 */
class DecoratorManager
{
    /**
     * @var DecoratorInterface[] The decorators
     */
    protected array $decorators = [];

    /**
     * Add decorators.
     *
     * @param DecoratorInterface[] $decorators The decorators
     */
    public function addDecorators(\Traversable $decorators): void
    {
        foreach ($decorators as $decorator) {
            $this->addDecorator($decorator);
        }
    }

    /**
     * Add a decorator.
     */
    public function addDecorator(DecoratorInterface $decorator): void
    {
        $this->decorators[] = $decorator;
    }

    /**
     * Return if the entity is supported by the decorator manager.
     *
     * @param object|object[] $oneOrManyEntities One or many entities
     */
    public function isSupportedEntity(object|array $oneOrManyEntities): bool
    {
        if (\is_array($oneOrManyEntities) || $oneOrManyEntities instanceof \Traversable) {
            $atLeastOneEntity = false;

            foreach ($oneOrManyEntities as $entity) {
                if (!\is_object($entity) || !$this->isSupportedEntity($entity)) {
                    return false;
                }

                $atLeastOneEntity = true;
            }

            return $atLeastOneEntity;
        }

        foreach ($this->decorators as $decorator) {
            if ($decorator->supports($oneOrManyEntities)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get the decorator of one or many entities.
     *
     * @param object|object[] $oneOrManyEntities One or many entities
     *
     * @return DecoratorInterface|DecoratorInterface[] The decorator(s)
     *
     * @throws DecoratorException If the decorator has not been called
     */
    public function get(object|array $oneOrManyEntities): DecoratorInterface|array
    {
        if (
            is_array($oneOrManyEntities)
            || $oneOrManyEntities instanceof \ArrayAccess
            || $oneOrManyEntities instanceof \Traversable
        ) {
            return $this->getArray($oneOrManyEntities);
        }

        foreach ($this->decorators as $decorator) {
            if ($decorator->supports($oneOrManyEntities)) {
                // Clone to avoid references and return the same objects
                $decoratorClone = clone $decorator;
                $decoratorClone->setEntity($oneOrManyEntities);

                return $decoratorClone;
            }
        }

        throw new DecoratorException('The entity decorator has not been called for "'.\get_debug_type($oneOrManyEntities).'".');
    }

    /**
     * Get the decorators of the entities.
     *
     * @see \Lyssal\Entity\Decorator\DecoratorManager::get()
     *
     * @param array|\ArrayAccess|\Traversable $entities The entities
     */
    protected function getArray(array|\ArrayAccess|\Traversable $entities): array
    {
        $decorators = [];

        foreach ($entities as $i => $entity) {
            $decorators[$i] = $this->get($entity);
        }

        return $decorators;
    }
}
