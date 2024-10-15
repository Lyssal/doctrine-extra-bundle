<?php

namespace Lyssal\DoctrineExtraBundle\Exception;

/**
 * A decorator exception.
 */
class DecoratorException extends EntityException
{
    /**
     * Constructor.
     *
     * @param string $message The error message
     * @param int    $code    The error code
     */
    public function __construct(string $message, int $code = 500)
    {
        parent::__construct($message, $code);
    }
}
