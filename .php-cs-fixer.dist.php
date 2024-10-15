<?php

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__)
;

return (new PhpCsFixer\Config())
    ->setRules([
        '@Symfony' => true,
        'header_comment' => [
            'header' =>
                'Ce fichier fait partie d\'un projet Lyssal.'.
                "\n\n".'This file is part of a Lyssal project.'.
                "\n\n".'@copyright Rémi Leclerc'.
                "\n".'@author Rémi Leclerc',
            'comment_type' => 'PHPDoc',
        ],
    ])
    ->setFinder($finder)
;
