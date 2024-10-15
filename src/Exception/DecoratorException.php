<?php

/**
 * Ce fichier fait partie d'un projet Lyssal.
 *
 * This file is part of a Lyssal project.
 *
 * @copyright Rémi Leclerc
 * @author Rémi Leclerc
 */

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
