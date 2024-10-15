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

use Lyssal\DoctrineExtraBundle\Breadcrumb\BreadcrumbGenerator;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * The twig method to generate a breadcrumb.
 */
class BreadcrumbExtension extends AbstractExtension
{
    public function __construct(
        protected readonly Environment $templating,
        protected readonly BreadcrumbGenerator $breadcrumbGenerator,
        private readonly string $breadcrumbTemplate,
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('lyssal_breadcrumb', [$this, 'breadcrumb'], ['is_safe' => ['html']]),
        ];
    }

    /**
     * @see \Lyssal\EntityBundle\Breadcrumb\BreadcrumbGenerator::generate()
     */
    public function breadcrumb(...$items): string
    {
        return $this->templating->render($this->breadcrumbTemplate, [
            'breadcrumbs' => $this->breadcrumbGenerator->generate($items),
        ]);
    }
}
