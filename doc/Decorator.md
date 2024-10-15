# Decorator

The decorators permits you to create specific methods for an entity if you do not want or do not can add them in your `Entity`.
The advantage of the decorator is you can inject all services your need.

## Creation

Your `Decorator` class :

```php
namespace App\Decorator;

use App\Entity\MyEntity;
use Lyssal\DoctrineExtraBundle\Decorator\AbstractDecorator;

final class MyEntityDecorator extends AbstractDecorator
{
    public function __construct(private readonly MyService $myService)
    {
    }

    public function supports(object $entity): bool
    {
        return $entity instanceof MyEntity;
    }

    /**
     * Return the label of the status.
     * 
     * @return string The status label
     */
    public function getStatusLabel(): string
    {
        return $this->myService->getStatusLabel($this->entity);
    }
}
```

## Functionalities and use

Using the `DecoratorManager` service :

```php
use Lyssal\DoctrineExtraBundle\Decorator\DecoratorManager;

final class MyService
{
    public function __construct(private readonly DecoratorManager $decoratorManager)
    {
    }

    public function myFunction(MyEntity $entity): void
    {
        $myEntityDecorator = $this->decoratorManager->get($myEntity);
        $statusLabel = $myEntityDecorator->getStatusLabel();
    }
}
```

The decorators also works with array of entities :

```php
$myEntityDecorators = $this->decoratorManager->get($myEntities);

foreach ($myEntityDecorators as $myEntityDecorator) {
    echo $myEntityDecorator->getStatusLabel();
}
```

The decorators can have a lot of vocations :

* Return an URL :

```php
$myDecorator->getUrl();
```

* Return an image in HTML :

```php
$myDecorator->getIconHtml();
```

* Verify a right, an access :

```php
if ($periodDecorator->isOpen()) {
    // ...
}
```

* Etc...

```php
if ($periodDecorator->isFinished()) {
    echo 'Finished';
}

echo $periodDecorator->getDayCount();
```

If a joined entity has a decorator, the getter will also return a decorator.

```php
$myEntityDecorator = $this->decoratorManager->get($myEntity);
$myEntityDecorator->getTypes(); // If `MyEntityTypeDecorator` exists, it will return an array of decorators
```

## The Twig functions

You can you the `decorator()` function :

```twig
{# Display the avatar of the current user #}
{{ decorator(app.user).avatarHtml|raw_secure }}
```
