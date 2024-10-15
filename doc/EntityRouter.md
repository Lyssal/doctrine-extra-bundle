# The entity router

The entity router permits you to automtically generate an URL with your entities.

## How to use

Your entity have to implement `RoutableInterface`.

For example:

```php
namespace App\Entity;

use Lyssal\DoctrineExtraBundle\Entity\Router\RoutableInterface;

#[ORM\Entity]
class MyEntity implements RoutableInterface
{
    // My properties and methods

    public function getRouteProperties(): array
    {
        return ['my_route', ['myEntity' => $this->id]];
    }
}
```

The `getRouteProperties()` have to return the route name and its parameters.

## The entity router service

Use the `lyssal.entity_router` service to generate URL.

```php
$entityUrl = $this->container->get('lyssal.entity_router')->generate($myEntity);
```

```php
use Lyssal\DoctrineExtraBundle\Router\EntityRouterManager;

final class MyService
{
    public function __construct(private readonly EntityRouterManager $entityRouterManager)
    {
    }

    public function myFunction(MyEntity $entity): void
    {
        $myEntityUrl = $this->entityRouterManager->generate($myEntity);
    }
}
```

## The Twig function

You can you the `entity_path()` function:

```twig
<a href="{{ entity_path(my_entity) }}">Click here to show {{ appellation(my_entity) }}</a>
```
