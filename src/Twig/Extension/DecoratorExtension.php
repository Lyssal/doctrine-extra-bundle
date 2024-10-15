<?php

/**
 * Ce fichier fait partie d'un projet Lyssal.
 *
 * This file is part of a Lyssal project.
 *
 * @copyright Rémi Leclerc
 * @author Rémi Leclerc
 */

namespace Lyssal\DoctrineExtraBundle\Twig\Extension;

use Lyssal\DoctrineExtraBundle\Decorator\DecoratorInterface;
use Lyssal\DoctrineExtraBundle\Decorator\DecoratorManager;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * The Twig methods for the appellation service.
 */
class DecoratorExtension extends AbstractExtension
{
    public function __construct(protected readonly DecoratorManager $decoratorManager)
    {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('decorator', [$this, 'decorator']),
        ];
    }

    /**
     * Get the decorator of the object.
     */
    public function decorator(object|array $oneOrManyEntities): DecoratorInterface|array
    {
        return $this->decoratorManager->get($oneOrManyEntities);
    }
}
