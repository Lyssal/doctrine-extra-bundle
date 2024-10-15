<?php

namespace Lyssal\DoctrineExtraBundle\Appellation;

use Lyssal\DoctrineExtraBundle\Decorator\DecoratorInterface;
use Lyssal\DoctrineExtraBundle\Entity\Router\RoutableInterface;
use Lyssal\DoctrineExtraBundle\Exception\AppellationException;
use Lyssal\DoctrineExtraBundle\Router\EntityRouterManager;

class AppellationManager
{
    /**
     * The appellations.
     *
     * @var AppellationInterface[]
     */
    protected array $appellations = [];

    public function __construct(protected readonly EntityRouterManager $entityRouterManager)
    {
    }

    /**
     * Add Appellations.
     */
    public function addAppellations(\Traversable $appelations): void
    {
        foreach ($appelations as $appelation) {
            $this->addAppellation($appelation);
        }
    }

    /**
     * Add an Appellation.
     */
    public function addAppellation(AppellationInterface $appellation): void
    {
        $this->appellations[] = $appellation;
    }

    /**
     * Get the appellation service of the object.
     *
     * @param object $object The object
     *
     * @return AppellationInterface The appellation service
     */
    protected function getAppellationService(object $object): ?AppellationInterface
    {
        foreach ($this->appellations as $appellation) {
            if ($appellation->supports($object)) {
                return $appellation;
            }
        }

        return null;
    }

    /**
     * Get the appellation of the object.
     *
     * @throws AppellationException If the object has not a `__toString()` method and if the appellation does not exist
     */
    public function appellation(object $object): string
    {
        $appellationService = $this->getAppellationService($object);

        if (null !== $appellationService) {
            return $appellationService->appellation($object);
        }

        if (\method_exists($object, '__toString')) {
            return (string) $object;
        }

        if ($object instanceof DecoratorInterface) {
            return $this->appellation($object->getEntity());
        }

        throw new AppellationException('The appellation has not been called for "'.$object::class.'" and the class has not a `__toString()` method.');
    }

    /**
     * Get the HTML appellation of the object.
     *
     * @throws AppellationException If the object has not a `__toString()` method and if the appellation does not exist
     */
    public function appellationHtml(object $object): string
    {
        $appellationService = $this->getAppellationService($object);

        if (null !== $appellationService) {
            return $appellationService->appellationHtml($object);
        }

        $url = $object instanceof RoutableInterface
            ? $this->entityRouterManager->generate($object)
            : (
                ($object instanceof DecoratorInterface && $object->getEntity() instanceof RoutableInterface)
                ? $this->entityRouterManager->generate($object->getEntity())
                : null
            )
        ;

        if (null !== $url) {
            return '<a href="'.$url.'">'.$this->appellation($object).'</a>';
        }

        return $this->appellation($object);
    }
}
