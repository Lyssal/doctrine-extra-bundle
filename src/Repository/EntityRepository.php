<?php

/**
 * Ce fichier fait partie d'un projet Lyssal.
 *
 * This file is part of a Lyssal project.
 *
 * @copyright Rémi Leclerc
 * @author Rémi Leclerc
 */

namespace Lyssal\DoctrineExtraBundle\Repository;

use Doctrine\ORM\EntityRepository as DoctrineEntityRepository;
use Lyssal\DoctrineExtraBundle\Repository\Traits\QueryBuilderTrait;

/**
 * A Doctrine entity repository.
 */
class EntityRepository extends DoctrineEntityRepository
{
    use QueryBuilderTrait;

    /**
     * Get the identifier field names.
     *
     * @return string[] The identifier field names
     */
    public function getIdentifierFieldNames(): array
    {
        return $this->getClassMetadata()->getIdentifier();
    }

    /**
     * Return the single identifier field name of the entity.
     *
     * @return string The identifier field name
     *
     * @throws \Doctrine\ORM\Mapping\MappingException If the identifier is not unique
     */
    public function getSingleIdentifierFieldName(): string
    {
        return $this->getClassMetadata()->getSingleIdentifierFieldName();
    }

    /**
     * Get if the entity has the field.
     *
     * @param string $fieldName The field name
     *
     * @return bool If the field exists
     */
    public function hasField(string $fieldName): bool
    {
        foreach ($this->getEntityManager()->getMetadataFactory()->getAllMetadata() as $entityMetadata) {
            if ($entityMetadata->hasField($fieldName)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get if the entity has the association.
     *
     * @param string $fieldName The association's field name
     *
     * @return bool If the association exists
     */
    public function hasAssociation(string $fieldName): bool
    {
        foreach ($this->getEntityManager()->getMetadataFactory()->getAllMetadata() as $entityMetadata) {
            if ($entityMetadata->hasAssociation($fieldName)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get the entities count.
     *
     * @param array $conditions The conditions
     *
     * @return int The entities count
     */
    public function count(array $conditions = []): int
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();
        $queryBuilder
            ->select($queryBuilder->expr()->count('entity'))
            ->from($this->getClassName(), 'entity')
        ;

        $this
            ->processQueryBuilderConditions($queryBuilder, $conditions)
            ->processQueryBuilderHavings($queryBuilder, $conditions)
        ;

        return (int) $queryBuilder->getQuery()->getSingleScalarResult();
    }

    /**
     * @see \Doctrine\Persistence\ObjectManager::persist()
     */
    public function persist(object $entity): void
    {
        $this->getEntityManager()->persist($entity);
    }

    /**
     * @see \Doctrine\Persistence\ObjectManager::remove()
     */
    public function remove(object $entity): void
    {
        $this->getEntityManager()->remove($entity);
    }

    /**
     * @see \Doctrine\Persistence\ObjectManager::refresh()
     */
    public function refresh(object $entity): void
    {
        $this->getEntityManager()->refresh($entity);
    }

    /**
     * @see \Doctrine\Persistence\ObjectManager::flush()
     */
    public function flush(): void
    {
        $this->getEntityManager()->flush();
    }
}
