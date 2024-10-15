<?php

namespace Lyssal\DoctrineExtraBundle\Twig\Extension;

use Lyssal\DoctrineExtraBundle\Router\EntityRouterManager;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * The twig method to generate an entity URL.
 */
class PathExtension extends AbstractExtension
{
    public function __construct(protected readonly EntityRouterManager $entityRouterManager)
    {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('entity_path', [$this, 'path']),
        ];
    }

    /**
     * @see \Lyssal\DoctrineExtraBundle\Router\EntityRouterInterface::generate()
     */
    public function path(object $routable, array $parameters = [], int $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH): ?string
    {
        return $this->entityRouterManager->generate($routable, $parameters, $referenceType);
    }
}
