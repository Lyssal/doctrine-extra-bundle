<?php

/**
 * Ce fichier fait partie d'un projet Lyssal.
 *
 * This file is part of a Lyssal project.
 *
 * @copyright Rémi Leclerc
 * @author Rémi Leclerc
 */

namespace Lyssal\DoctrineExtraBundle\Administrator;

use Doctrine\ORM\EntityManagerInterface;

/**
 * the entity administrators' manager.
 */
class EntityAdministratorManager
{
    /**
     * @var EntityAdministratorInterface[] The entity administrator handlers
     */
    protected array $entityAdministrators = [];

    public function __construct(protected readonly EntityManagerInterface $doctrineEntityManager)
    {
    }

    /**
     * Set entity administrators.
     *
     * @param EntityAdministratorInterface[] $entityAdministrators The entity administrators
     */
    public function addEntityAdministrators(\Traversable $entityAdministrators): void
    {
        foreach ($entityAdministrators as $entityAdministrator) {
            $this->addEntityAdministrator($entityAdministrator);
        }
    }

    /**
     * Add an entity administrator.
     */
    public function addEntityAdministrator(EntityAdministratorInterface $entityAdministrator): void
    {
        $this->entityAdministrators[] = $entityAdministrator;
    }

    /**
     * Get the administrator of the entity.
     */
    public function get(string $entityClass): EntityAdministratorInterface
    {
        foreach ($this->entityAdministrators as $entityAdministrator) {
            if ($entityClass === $entityAdministrator->getClass()) {
                return $entityAdministrator;
            }
        }

        // Create a new EntityAdministrator if not exists
        $entityAdministrator = new EntityAdministrator($this->doctrineEntityManager, $entityClass);
        $this->addEntityAdministrator($entityAdministrator);

        return $entityAdministrator;
    }
}
