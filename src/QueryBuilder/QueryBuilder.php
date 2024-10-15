<?php

namespace Lyssal\DoctrineExtraBundle\QueryBuilder;

/**
 * Constants used in the entity repository.
 */
class QueryBuilder
{
    /**
     * @var string The entity alias
     */
    public const string ALIAS = 'entity';

    /**
     * @var string Extra for addSelect()
     */
    public const string SELECTS = 'selects';

    /**
     * @var string Extra for leftJoin()
     */
    public const string LEFT_JOINS = 'leftJoins';

    /**
     * @var string Extra for innerJoin()
     */
    public const string INNER_JOINS = 'innerJoins';

    /**
     * @var string Extra for andGroupBy()
     */
    public const string GROUP_BYS = 'groupBys';

    /**
     * @var string For (x OR y OR ...)
     */
    public const string OR_WHERE = '__OR_WHERE__';

    /**
     * @var string For (x AND y AND ...)
     */
    public const string AND_WHERE = '__AND_WHERE__';

    /**
     * @var string For a WHERE ... LIKE ...
     */
    public const string WHERE_LIKE = '__LIKE__';

    /**
     * @var string For a WHERE ... NOT LIKE ...
     */
    public const string WHERE_NOT_LIKE = '__NOT_LIKE__';

    /**
     * @var string For a WHERE ... IN (...)
     */
    public const string WHERE_IN = '__IN__';

    /**
     * @var string For a WHERE ... NOT IN (...)
     */
    public const string WHERE_NOT_IN = '__NOT_IN__';

    /**
     * @var string For a WHERE ... IS NULL
     */
    public const string WHERE_NULL = '__IS_NULL__';

    /**
     * @var string For a WHERE ... IS NOT NULL
     */
    public const string WHERE_NOT_NULL = '__IS_NOT_NULL__';

    /**
     * @var string For a x = y
     */
    public const string WHERE_EQUAL = '__WHERE_EQUAL__';

    /**
     * @var string For a x != y
     */
    public const string WHERE_NOT_EQUAL = '__WHERE_NOT_EQUAL__';

    /**
     * @var string For a x < y
     */
    public const string WHERE_LESS = '__WHERE_LESS__';

    /**
     * @var string For a x <= y
     */
    public const string WHERE_LESS_OR_EQUAL = '__WHERE_LESS_OR_EQUAL__';

    /**
     * @var string For a x > y
     */
    public const string WHERE_GREATER = '__WHERE_GREATER__';

    /**
     * @var string For a x >= y
     */
    public const string WHERE_GREATER_OR_EQUAL = '__WHERE_GREATER_OR_EQUAL__';

    /**
     * @var string For a HAVING (x OR y OR ...)
     */
    public const string OR_HAVING = '__OR_HAVING__';

    /**
     * @var string For a HAVING (x AND y AND ...)
     */
    public const string AND_HAVING = '__AND_HAVING__';

    /**
     * @var string For a HAVING x = y
     */
    public const string HAVING_EQUAL = '__HAVING_EQUAL__';

    /**
     * @var string For a HAVING x < y
     */
    public const string HAVING_LESS = '__HAVING_LESS__';

    /**
     * @var string For a HAVING x <= y
     */
    public const string HAVING_LESS_OR_EQUAL = '__HAVING_LESS_OR_EQUAL__';

    /**
     * @var string For a HAVING x > y
     */
    public const string HAVING_GREATER = '__HAVING_GREATER__';

    /**
     * @var string For a HAVING x >= y
     */
    public const string HAVING_GREATER_OR_EQUAL = '__HAVING_GREATER_OR_EQUAL__';
}
