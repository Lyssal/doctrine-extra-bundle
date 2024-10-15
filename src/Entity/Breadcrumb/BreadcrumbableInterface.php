<?php

/**
 * Ce fichier fait partie d'un projet Lyssal.
 *
 * This file is part of a Lyssal project.
 *
 * @copyright Rémi Leclerc
 * @author Rémi Leclerc
 */

namespace Lyssal\DoctrineExtraBundle\Entity\Breadcrumb;

/**
 * Interface for generate breadcrumbs with entities.
 */
interface BreadcrumbableInterface
{
    /**
     * Get the breadcrumb parent.
     */
    public function getBreadcrumbParent(): ?BreadcrumbableInterface;
}
