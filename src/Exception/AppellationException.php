<?php

namespace Lyssal\DoctrineExtraBundle\Exception;

/**
 * An appellation exception.
 */
class AppellationException extends \Exception
{
    public function __construct(string $message, int $code = 500)
    {
        parent::__construct($message, $code);
    }
}
