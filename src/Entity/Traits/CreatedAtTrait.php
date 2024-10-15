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
 * To add a createdAt property in an entity.
 *
 * Do not forget to add the `HasLifecycleCallbacks` annotation in your entity.
 */
trait CreatedAtTrait
{
    /**
     * The creation date.
     */
    #[ORM\Column(options: ['default' => 'CURRENT_TIMESTAMP'])]
    protected \DateTime $createdAt;

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * Init the creation date.
     */
    #[ORM\PrePersist]
    public function initCreatedAt()
    {
        $this->createdAt = new \DateTime();
    }
}
