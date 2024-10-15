# The breadcrumbs

You can automatically generate breadcrumbs for your entity pages.

## How to use

First, your entities must implements `BreadcrumbableInterface`.

By default, to generate names, we use the [Appellation](doc/Appellation.md) so if your entity do not have an Appellation, do not forget the `__toString()` method.

By default, to generate links, we use the [entity router](EntityRouter.md) so your entities have to implements `RoutableInterface`.

### Example

```php
namespace App\Entity;

use Lyssal\DoctrineExtraBundle\Entity\Breadcrumb\BreadcrumbableInterface;
use Lyssal\DoctrineExtraBundle\Entity\Router\RoutableInterface;

#[ORM\Entity]
class MyEntity implements BreadcrumbableInterface, RoutableInterface
{
    // My properties and methods

    public function getBreadcrumbParent(): ?BreadcrumbableInterface
    {
        // Here return the parent entity (or null if no parent)
        return $this->myParent;
    }

    public function getRouteProperties(): array
    {
        return ['my_route', ['myEntity' => $this->id]];
    }

    public function __toString()
    {
        return (string) $this->name;
    }
}
```

Just call the `lyssal_breadcrumb` function in your template to display the breadcrumb.

The first paramater either an entity or a string.
If you want to add a parent, set a second parameter.
If you still want to add an other parent, set a third parameter, etc.

```twig
{{ lyssal_breadcrumb(my_entity) }}

{# Add a parent element #}
{{ lyssal_breadcrumb(my_entity, '<a href="#">My parent</a>') }}

{# ... > My entity > Edit #}
{{ lyssal_breadcrumb('edit'|trans, my_entity) }}
```

## The template

You can define your breadcrumb template in config:

```yaml
lyssal_doctrine_extra:
    breadcrumbs:
        template: '@App/breadcrumbs.html.twig'
```

By default, it is a simple HTML list but you also can use a defined template:

```yaml
# If you use Foundation 6
template: '@LyssalDoctrineExtra/_breadcrumbs/foundation_6.html.twig'

# If you use Bootstrap 4
template: '@LyssalDoctrineExtra/_breadcrumbs/bootstrap_4.html.twig'

# By default
template: '@LyssalDoctrineExtra/_breadcrumbs/dedfault.html.twig'
```

## Add a root element

To automatically add a root element, specify in your config:

```yaml
LyssalDoctrineExtra:
    breadcrumbs:
        root: '<a href="/">HOME</a>'
```
