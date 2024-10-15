<?php

/**
 * Ce fichier fait partie d'un projet Lyssal.
 *
 * This file is part of a Lyssal project.
 *
 * @copyright Rémi Leclerc
 * @author Rémi Leclerc
 */

namespace Lyssal\DoctrineExtraBundle\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

/**
 * To add a updatedAt property in an entity.
 *
 * Do not forget to add the `HasLifecycleCallbacks` attribute in your entity.
 */
trait UpdatedAtTrait
{
    /**
     * The update date.
     */
    #[ORM\Column(nullable: false, options: ['default' => 'CURRENT_TIMESTAMP'])]
    protected \DateTime $updatedAt;

    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * Init the update date.
     */
    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function initUpdatedAt()
    {
        $this->updatedAt = new \DateTime();
    }
}
