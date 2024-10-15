# The Entity administrator

You can use the Lyssal Doctrine Extra Administrator as the base of all your entity administrators.
The administrator is connected to the entity repository and permits to read, save and remove entities.

## Use

### Create your specific administrator

```php
namespace App\Doctrine\Administrator;

use App\Entity\MyEntity;
use Lyssal\DoctrineExtraBundle\Administrator\EntityAdministrator;

/**
 * The MyEntity's administrator.
 */
final class MyEntityAdministrator extends EntityAdministrator
{
    // Your methods

    public function getClass(): string
    {
        return MyEntity::class;
    }
}
```

### Automatically generate entity administrators

If you do not want to create specific methods, you do not need to create a class.
Just use the `lyssal.entity_administrator` service:

```php
namespace App\Toto;

use App\Entity\MyEntity;
use Lyssal\DoctrineExtraBundle\Administrator\EntityAdministratorManager;

final class MyClass
{
    public function foo(EntityAdministratorManager $entityAdministratorManager)
    {
        $myEntityAdministrator = $entityAdministratorManager->get(MyEntity::class);
    
        $myEntities = $myEntityAdministrator->findLikeBy([
            'name' => '%toto%',
        ]);
    
        ...
    }
}
```

The Lyssal entity administrator will generate a default administrator with a lot of useful methods (to read, write, remove, access the entity repository, etc) or will use your own administrator.

If you want to create your own administrator, your service has to extend `Lyssal\DoctrineExtraBundle\Administrator\EntityAdministrator` (or implement `Lyssal\DoctrineExtraBundle\Administrator\EntityAdministratorInterface`).

## The method parameters

Read the phpdoc for the list of the methods.

### The `$conditions` parameter

The Lyssal Doctrine Extra library extends this parameter.

For example:

```php
use Lyssal\DoctrineExtraBundle\QueryBuilder\QueryBuilder;

// genderParent IS NULL
$conditions = [
    'genderParent' => null,
];
// or
$conditions = [
    QueryBuilder::WHERE_NULL => 'genderParent',
];
// or (if we want many WHERE_NULL)
$conditions = [[
    QueryBuilder::AND_WHERE => [
        [QueryBuilder::WHERE_NULL => 'genderParent'],
        [QueryBuilder::WHERE_NULL => '...'],
    ],
];

// (gender = $gender OR genderParent = $gender) AND gender.name LIKE '%trategi%'
$conditions = [
    QueryBuilder::OR_WHERE => [
        'gender' => $gender,
        'genderParent' => $gender,
    ],
    QueryBuilder::WHERE_LIKE => [
        'gender.name' => '%trategi%',
    ],
];

// (gender.name LIKE '%trategi%' OR gender.name LIKE '%éflexio%')
$conditions = [
    QueryBuilder::OR_WHERE => [
        [QueryBuilder::WHERE_LIKE => array('gender.name' => '%trategi%')],
        [QueryBuilder::WHERE_LIKE => array('gender.name' => '%éflexio%')],
    ],
];
```

Possibilities for the `$conditions` parameter are:

* `QueryBuilder::OR_WHERE` : x OR y OR ...
* `QueryBuilder::AND_WHERE` : x AND y AND ...
* `QueryBuilder::WHERE_LIKE` : x LIKE y
* `QueryBuilder::WHERE_IN` : x IN (y1, y2...)
* `QueryBuilder::WHERE_NULL` : x IS NULL
* `QueryBuilder::WHERE_NOT_NULL` : x IS NOT NULL
* `QueryBuilder::WHERE_EQUAL` : x = y
* `QueryBuilder::WHERE_LESS` : x < y
* `QueryBuilder::WHERE_LESS_OR_EQUAL` : x <= y
* `QueryBuilder::WHERE_GREATER` : x > y
* `QueryBuilder::WHERE_GREATER_OR_EQUAL` : x >= y

and for HAVING:

* `QueryBuilder::OR_HAVING`
* `QueryBuilder::AND_HAVING`
* `QueryBuilder::HAVING_EQUAL`
* `QueryBuilder::HAVING_LESS`
* `QueryBuilder::HAVING_LESS_OR_EQUAL`
* `QueryBuilder::HAVING_GREATER`
* `QueryBuilder::HAVING_GREATER_OR_EQUAL`


### The `$extras` parameter

The Lyssal Doctrine Extra library adds this new parameter.

For example:

```php
$extras = [
    // innerJoin('entity.city', 'city')
    // innerJoin('city.country', 'country')
    QueryBuilder::INNER_JOINS => [
        'city' => 'city',
        'city.country' => 'country',
    ],
    // select('entity', 'city', 'country')
    QueryBuilder::SELECTS => [
        QueryBuilder::ALIAS,
        'city',
        'country',
    ],
];
```

Possibilities for the `$extras` parameter are:

* `QueryBuilder::SELECTS` : Calls the `select()` method. You can add a joined entity or any other value (an entity property, a COUNT, etc). Do not forget to add `QueryBuilder::ALIAS` if you need the main entity or use a joined entity
* `QueryBuilder::LEFT_JOINS`
* `QueryBuilder::INNER_JOINS`
* `QueryBuilder::GROUP_BYS`


## The default `orderBy`

You can define a default `orderBy` extending the `$DEFAULT_ORDER_BY` static property.

```php
use App\Entity\MyEntity;
use Lyssal\DoctrineExtraBundle\Administrator\EntityAdministrator;

final class MyEntityAdministrator extends EntityAdministrator
{
    public static array $DEFAULT_ORDER_BY = [
        'position' => 'ASC',
    ];

    public function getClass(): string
    {
        return MyEntity::class;
    }
}
```

And if you do not define the `$orderBy` parameter (in `findBy()`, `findAll()`, etc), this value will be always used.
