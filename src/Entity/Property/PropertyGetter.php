<?php

namespace Lyssal\DoctrineExtraBundle\Entity\Property;

use Lyssal\DoctrineExtraBundle\Exception\EntityException;

/**
 * Get an entity property.
 */
class PropertyGetter
{
    public function __construct(protected object $entity)
    {
    }

    /**
     * Get a value to the entity property.
     *
     * @param string $property The entity property
     * @param array  $args     The method arguments
     *
     * @return mixed The property value
     *
     * @throws EntityException If the getter method is not found
     */
    public function get(string $property, array $args = []): mixed
    {
        if (\method_exists($this->entity, $property)) {
            return \call_user_func_array([$this->entity, $property], $args);
        }

        if (\method_exists($this->entity, 'get'.ucfirst($property))) {
            return \call_user_func_array([$this->entity, 'get'.ucfirst($property)], $args);
        }

        if (\method_exists($this->entity, 'is'.ucfirst($property))) {
            return \call_user_func_array([$this->entity, 'is'.ucfirst($property)], $args);
        }

        throw new EntityException('No getter function has been found for the property "'.$property.'" of the object "'.$this->entity::class.'".');
    }
}
