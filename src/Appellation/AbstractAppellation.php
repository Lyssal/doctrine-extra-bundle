<?php

/**
 * Ce fichier fait partie d'un projet Lyssal.
 *
 * This file is part of a Lyssal project.
 *
 * @copyright Rémi Leclerc
 * @author Rémi Leclerc
 */

namespace Lyssal\DoctrineExtraBundle\Appellation;

use Lyssal\DoctrineExtraBundle\Decorator\AbstractDecorator;
use Lyssal\DoctrineExtraBundle\Router\EntityRouterManager;

/**
 * The abstract appellation which use the __toString method by default.
 */
abstract class AbstractAppellation implements AppellationInterface
{
    public function __construct(protected readonly EntityRouterManager $entityRouterManager)
    {
    }

    /**
     * @see AppellationInterface::appellation()
     */
    public function appellation(object $object): string
    {
        if ($object instanceof AbstractDecorator && !\method_exists($object, '__toString')) {
            return $this->appellation($object->getEntity());
        }

        return (string) $object;
    }

    /**
     * @see AppellationInterface::appellationHtml()
     */
    public function appellationHtml(object $object): string
    {
        $url = $this->entityRouterManager->generate($object);

        if (null !== $url) {
            return '<a href="'.$url.'">'.$this->appellation($object).'</a>';
        }

        return $this->appellation($object);
    }
}
