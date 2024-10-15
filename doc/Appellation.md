# Appellation

The appellations permit to use and display the appellation of an object.
A simple appellation is a string which defines the object.

The appellation will help you to have a consistency on all the appellation of your application.
And the HTML appellation will be helpful for example for easily display a link or an icon with the name of one of yours entities.


## Functionalities

### The Symfony service

Use the `lyssal.appellation` service to use appellations :

```php
use Lyssal\DoctrineExtraBundle\Appellation\AppellationManager;

final class MyClass
{
    public function __construct(private readonly AppellationManager $appellationManager)
    {
    }

    public function myFunction(MyEntity $entity): void
    {
        $appellation = $this->appellationManager->appellation($entity);
        $appellationHtml = $this->appellationManager->appellationHtml($entity);
    }
}
```

### The functions

#### The simple appellation

By default, it uses the `__toString()` method of the object.

Example of use in PHP :

```php
$cityAppellation = $this->appellationManager->appellation($city);
```

Example of use in Twig :

```twig
<p>Hello {{ appellation(user) }} !</p>
```


#### The HTML appellation

By default, it is the same as the simple appellation and if your entity implements the `RoutableInterface`, a link (`<a href="">`) will be added.

Example of use in PHP :

```php
$cityAppellationHtml = $this->appellationManager->appellationHtml($city);
```

Example of use in Twig :

```twig
<p>Click the city : {{ appellation_html(city) }}.</p>
```

This could render :

```twig
<p>Click the city : <a href="/Cities/Paris">Paris</a>.</p>
```

## Create your appellation

If you want to customize the appellation of your entity `App\Entity\MyEntity`, simply create an `MyEntityAppellation` class like this :

```php
namespace App\Appellation;

use App\Entity\MyEntity;
use Lyssal\DoctrineExtraBundle\Appellation\AbstractAppellation;

final class MyEntityAppellation extends AbstractAppellation
{
    public function supports(object $object): bool
    {
        return $object instanceof MyEntity;
    }

    public function appellation(object $object): string
    {
        return $object->__toString().' (#'.$object->getId().')';
    }

    /**
     * Not neccessary if MyEntity implements `RoutableInterface`.
     */
    public function appellationHtml(object $object): string
    {
        return '<a href="'.$this->router->generate('app_myentity_view', array('entity' => $object->getId())).'">'.$this->appellation($object).'</a>';
    }
}
```
