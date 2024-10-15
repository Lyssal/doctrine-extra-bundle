<?php

namespace Lyssal\DoctrineExtraBundle\Twig\Extension;

use Lyssal\DoctrineExtraBundle\Appellation\AppellationManager;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * The Twig methods for the appellation service.
 */
class AppellationExtension extends AbstractExtension
{
    public function __construct(protected readonly AppellationManager $appellationManager)
    {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('appellation', [$this, 'appellation'], ['is_safe' => ['html']]),
            new TwigFunction('appellation_html', [$this, 'appellationHtml'], ['is_safe' => ['html']]),
        ];
    }

    /**
     * Get the appellation of the object.
     */
    public function appellation(object $object): string
    {
        return $this->appellationManager->appellation($object);
    }

    /**
     * Get the HTML appellation of the object.
     */
    public function appellationHtml(object $object): string
    {
        return $this->appellationManager->appellationHtml($object);
    }
}
