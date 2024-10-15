<?php

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
