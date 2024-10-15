<?php

namespace Lyssal\DoctrineExtraBundle\Appellation;

use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

/**
 * The interface for the appellation handler.
 */
#[AutoconfigureTag('lyssal.appellation')]
interface AppellationInterface
{
    /**
     * Return if the object is supported by the appelation manager.
     */
    public function supports(object $object): bool;

    /**
     * Return the simple appelation of the object.
     */
    public function appellation(object $object): string;

    /**
     * Return the HTML appelation of the object.
     */
    public function appellationHtml(object $object): string;
}
