<?php

namespace Lyssal\DoctrineExtraBundle\Administrator;

use Doctrine\ORM\EntityManagerInterface;
use Lyssal\DoctrineExtraBundle\Entity\Property\PropertySetter;
use Lyssal\DoctrineExtraBundle\Exception\OrmException;
use Lyssal\DoctrineExtraBundle\QueryBuilder\QueryBuilder;
use Lyssal\DoctrineExtraBundle\Repository\EntityRepository;

class EntityAdministrator implements EntityAdministratorInterface
{
    /**
     * The default orderBy parameter.
     */
    public static array $DEFAULT_ORDER_BY = [];

    /**
     * The entity class.
     */
    protected ?string $class;

    /**
     * The Doctrine entity repository.
     */
    protected EntityRepository $repository;

    public function __construct(protected readonly EntityManagerInterface $entityManager, ?string $entityClass = null)
    {
        $this->class = $entityClass;
        $repository = $this->entityManager->getRepository($this->getClass());

        if (!$repository instanceof EntityRepository) {
            throw new OrmException('To use an Entity Administrator, the Entity repository must extends "'.EntityRepository::class.'".');
        }

        $this->repository = $repository;
    }

    public function getRepository(): EntityRepository
    {
        return $this->repository;
    }

    /**
     * @throws OrmException If the entity class is not found
     */
    public function getClass(): string
    {
        if (null === $this->class) {
            throw new OrmException('You have to inject the `class` property or redefine the getClass() method.');
        }

        return $this->class;
    }

    public function findBy(array $conditions = [], array $orderBy = [], ?int $limit = null, ?int $offset = null, array $extras = []): array
    {
        if (empty($orderBy)) {
            $orderBy = static::$DEFAULT_ORDER_BY;
        }

        return $this->getRepository()->getQueryBuilderFindBy($conditions, $orderBy, $limit, $offset, $extras)->getQuery()->getResult();
    }

    public function findLikeBy(array $conditions = [], array $orderBy = [], ?int $limit = null, ?int $offset = null): array
    {
        if (empty($orderBy)) {
            $orderBy = static::$DEFAULT_ORDER_BY;
        }

        $likes = [QueryBuilder::AND_WHERE => []];

        foreach ($conditions as $i => $condition) {
            $likes[QueryBuilder::AND_WHERE][] = [QueryBuilder::WHERE_LIKE => [$i => $condition]];
        }

        return $this->getRepository()->getQueryBuilderFindBy($likes, $orderBy, $limit, $offset)->getQuery()->getResult();
    }

    public function findOneBy(array $conditions = [], array $orderBy = [], array $extras = []): ?object
    {
        if (empty($orderBy)) {
            $orderBy = static::$DEFAULT_ORDER_BY;
        }

        return $this->getRepository()->getQueryBuilderFindBy($conditions, $orderBy, 1, null, $extras)->getQuery()->getOneOrNullResult();
    }

    public function findOneById(mixed $id, array $extras = []): ?object
    {
        if (\count($extras) > 0) {
            return $this->getRepository()->getQueryBuilderFindBy([$this->getSingleIdentifierFieldName() => $id], extras: $extras)->getQuery()->getOneOrNullResult();
        }

        return $this->entityManager->find($this->getClass(), $id);
    }

    public function findAll(array $orderBy = []): array
    {
        if (empty($orderBy)) {
            $orderBy = static::$DEFAULT_ORDER_BY;
        }

        return $this->getRepository()->findBy([], $orderBy);
    }

    public function findByKeyedById(array $conditions = [], array $orderBy = [], ?int $limit = null, ?int $offset = null, array $extras = []): array
    {
        return $this->getEntitiesKeyedById($this->findBy($conditions, $orderBy, $limit, $offset, $extras));
    }

    public function findLikeByKeyedById(array $conditions = [], array $orderBy = [], ?int $limit = null, ?int $offset = null): array
    {
        return $this->getEntitiesKeyedById($this->findLikeBy($conditions, $orderBy, $limit, $offset));
    }

    public function findAllKeyedById(array $orderBy = []): array
    {
        return $this->getEntitiesKeyedById($this->findAll($orderBy));
    }

    /**
     * @throws OrmException                           If the parameter is not an array or a Traversable
     * @throws \Doctrine\ORM\Mapping\MappingException If the identifier is not unique
     * @throws OrmException                           If the entities have not the identifier getter method
     * @throws OrmException                           If at least one entity is not an instance of the managed class
     */
    public function getEntitiesKeyedById(array $entities): array
    {
        if (!\is_array($entities) && !($entities instanceof \Traversable)) {
            throw new OrmException('The entities parameter must be an array or a Traversable.');
        }

        $class = $this->getClass();
        $identifier = $this->getSingleIdentifierFieldName();
        $identifierGetter = 'get'.ucfirst($identifier);

        if (!\method_exists($class, $identifierGetter)) {
            throw new OrmException('The entity "'.$class.'" does not have the "'.$identifierGetter.'()" method.');
        }

        $entitiesById = [];

        foreach ($entities as $entity) {
            if (!($entity instanceof $class)) {
                throw new OrmException('All the entities must be objects of type "'.$class.'" (type "'.(is_object($entity) ? get_class($entity) : gettype($entity)).'" found).');
            }

            $entitiesById[$entity->$identifierGetter()] = $entity;
        }

        return $entitiesById;
    }

    public function count(array $conditions = []): int
    {
        return $this->getRepository()->count($conditions);
    }

    /**
     * @throws \Lyssal\DoctrineExtraBundle\Exception\EntityException If the setter method is not found
     */
    public function create(array $propertyValues = []): object
    {
        $class = $this->getClass();
        $entityGetter = new PropertySetter(new $class());

        return $entityGetter->set($propertyValues);
    }

    public function save(object|array $oneOrManyEntities): void
    {
        $this->persist($oneOrManyEntities);
        $this->flush();
    }

    public function persist(object|array $oneOrManyEntities): void
    {
        if (\is_array($oneOrManyEntities) || $oneOrManyEntities instanceof \Traversable) {
            foreach ($oneOrManyEntities as $entity) {
                $this->entityManager->persist($entity);
            }

            return;
        }

        $this->entityManager->persist($oneOrManyEntities);
    }

    public function flush(): void
    {
        $this->entityManager->flush();
    }

    public function detach(object $entity): void
    {
        $this->entityManager->detach($entity);
    }

    public function remove(object|array $oneOrManyEntities): void
    {
        if (\is_array($oneOrManyEntities) || $oneOrManyEntities instanceof \Traversable) {
            foreach ($oneOrManyEntities as $entity) {
                $this->entityManager->remove($entity);
            }

            return;
        }

        $this->entityManager->remove($oneOrManyEntities);
    }

    public function delete(object|array $oneOrManyEntities): void
    {
        $this->remove($oneOrManyEntities);
        $this->flush();
    }

    public function removeAll(bool $initAutoIncrement = false): void
    {
        $this->remove($this->findAll());

        if ($initAutoIncrement) {
            $this->initAutoIncrement();
        }
    }

    public function deleteAll(bool $initAutoIncrement = false): void
    {
        $this->removeAll($initAutoIncrement);
        $this->flush();
    }

    /**
     * @throws OrmException If the entity is not an instance of the managed class
     * @throws OrmException If the entity have not the identifier getter methods
     */
    public function exists(object $entity): bool
    {
        $class = $this->getClass();

        if (!$entity instanceof $class) {
            throw new OrmException('The entity must be an object of type "'.$class.'" (type "'.\get_debug_type($entity).'" found).');
        }

        foreach ($this->getIdentifierFieldNames() as $identifierFieldName) {
            $identifierGetter = 'get'.ucfirst($identifierFieldName);

            if (!\method_exists($class, $identifierGetter)) {
                throw new OrmException('The entity "'.$class.'" does not have the "'.$identifierGetter.'()" method.');
            }

            if (null === \call_user_func_array([$entity, $identifierGetter], [])) {
                return false;
            }
        }

        return true;
    }

    public function truncate(bool $initAutoIncrement = false): void
    {
        $this->entityManager->getConnection()->prepare('TRUNCATE TABLE '.$this->getTableName())->executeQuery();

        if ($initAutoIncrement) {
            $this->initAutoIncrement();
        }
    }

    public function initAutoIncrement(): void
    {
        $this->setAutoIncrement(1);
    }

    public function setAutoIncrement($autoIncrementValue): void
    {
        $this->entityManager->getConnection()->prepare('ALTER TABLE '.$this->getTableName().' auto_increment = '.$autoIncrementValue)->executeQuery();
    }

    public function getTableName(): string
    {
        return $this->entityManager->getMetadataFactory()->getMetadataFor($this->repository->getClassName())->getTableName();
    }

    public function getIdentifierFieldNames(): array
    {
        return $this->getRepository()->getIdentifierFieldNames();
    }

    /**
     * @throws \Doctrine\ORM\Mapping\MappingException If the identifier is not unique
     */
    public function getSingleIdentifierFieldName(): string
    {
        return $this->getRepository()->getSingleIdentifierFieldName();
    }

    public function hasField(string $fieldName): bool
    {
        return $this->getRepository()->hasField($fieldName);
    }

    public function hasAssociation(string $fieldName): bool
    {
        return $this->getRepository()->hasAssociation($fieldName);
    }
}
