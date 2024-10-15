# The entity repository

See the [Lyssal Doctrine Extra Administrator documentation](Administrator.md) for more informations about the method parameters.

## Use

### By default

```yaml
doctrine:
    orm:
        default_repository_class: 'Lyssal\DoctrineExtraBundle\Repository\EntityRepository'
```

### Extends

Use like this :

```php
namespace App\Doctrine\Repository;

use Lyssal\DoctrineExtraBundle\Repository\EntityRepository;

final class MyEntityRepository extends EntityRepository
{
}
```

```php
namespace App\Entity;

use App\Doctrine\Repository\MyEntityRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MyEntityRepository::class)]
class MyEntity
{
    //...
}
```
