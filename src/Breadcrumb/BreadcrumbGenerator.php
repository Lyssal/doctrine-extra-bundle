<?php

namespace Lyssal\DoctrineExtraBundle\Breadcrumb;

use Lyssal\DoctrineExtraBundle\Appellation\AppellationManager;
use Lyssal\DoctrineExtraBundle\Decorator\DecoratorInterface;
use Lyssal\DoctrineExtraBundle\Entity\Breadcrumb\BreadcrumbableInterface;
use Lyssal\DoctrineExtraBundle\Model\Breadcrumb\Breadcrumb;
use Lyssal\DoctrineExtraBundle\Router\EntityRouterManager;

/**
 * Generate a breadcrumb with Parentable and ManyParentable interfaces.
 */
class BreadcrumbGenerator
{
    public function __construct(
        protected readonly AppellationManager $appellationManager,
        protected readonly EntityRouterManager $entityRouterManager,
        protected readonly ?string $breadcrumbRoot = null,
    ) {
    }

    /**
     * Generate the breadcrumb array.
     *
     * @param mixed ...$items The breadcrumb items
     *
     * @return array The breadcrumb
     */
    public function generate(...$items): array
    {
        $items[] = $this->breadcrumbRoot;
        $breadcrumbs = [];

        foreach ($items as $item) {
            $breadcrumbs = [...$breadcrumbs, ...$this->getBreadcrumbPart($item)];
        }

        return \array_reverse($breadcrumbs);
    }

    /**
     * Generate a part of the breadcrumb.
     */
    protected function getBreadcrumbPart(string|\Stringable|array|\Traversable|DecoratorInterface|null $item): array
    {
        if (null === $item) {
            return [];
        }

        $breadcrumbs = [];

        if (\is_array($item) || \is_iterable($item)) {
            foreach ($item as $subItem) {
                $breadcrumbs = array_merge($breadcrumbs, $this->getBreadcrumbPart($subItem));
            }

            return $breadcrumbs;
        }

        if ($item instanceof DecoratorInterface && !$item instanceof BreadcrumbableInterface) {
            $item = $item->getEntity();
        }

        $breadcrumbs[] = $this->formatBreadcrumb($item);

        if ($item instanceof BreadcrumbableInterface) {
            $breadcrumbs = array_merge($breadcrumbs, $this->getBreadcrumbPart($item->getBreadcrumbParent()));
        }

        return $breadcrumbs;
    }

    /**
     * Format the breadcrumb element.
     */
    protected function formatBreadcrumb(string|\Stringable|array|\Traversable|DecoratorInterface|null $breadcrumb): Breadcrumb
    {
        return (new Breadcrumb())
            ->setLabel(
                \is_object($breadcrumb)
                ? $this->appellationManager->appellation($breadcrumb)
                : (string) $breadcrumb
            )
            ->setLink(\is_object($breadcrumb) ? $this->entityRouterManager->generate($breadcrumb) : null)
        ;
    }
}
