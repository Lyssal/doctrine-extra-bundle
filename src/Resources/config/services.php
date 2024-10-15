<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Lyssal\DoctrineExtraBundle\Appellation\AppellationManager;
use Lyssal\DoctrineExtraBundle\Breadcrumb\BreadcrumbGenerator;
use Lyssal\DoctrineExtraBundle\Decorator\DecoratorManager;
use Lyssal\DoctrineExtraBundle\Router\EntityRouterManager;
use Lyssal\DoctrineExtraBundle\Twig\Extension\BreadcrumbExtension;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();

    $services
        ->defaults()
            ->autowire()
            ->autoconfigure()
            ->private()
        ->load('Lyssal\\DoctrineExtraBundle\\', '../../')
        ->exclude('../../{DependencyInjection,Entity,Exception,QueryBuilder,Resources}')
    ;

    $services
        ->set('lyssal.appellation', AppellationManager::class)
            ->public()
            ->call('addAppellations', [tagged_iterator('lyssal.appellation')])
        ->alias(AppellationManager::class, 'lyssal.appellation')
    ;

    $services
        ->set('lyssal.decorator', DecoratorManager::class)
            ->public()
            ->call('addDecorators', [tagged_iterator('lyssal.decorator')])
        ->alias(DecoratorManager::class, 'lyssal.decorator')
    ;

    $services
        ->set('lyssal.entity_router', EntityRouterManager::class)
            ->public()
            ->call('addEntityRouters', [tagged_iterator('lyssal.entity_router')])
        ->alias(EntityRouterManager::class, 'lyssal.entity_router')
    ;

    $services
        ->set(BreadcrumbExtension::class)
        ->bind('$breadcrumbTemplate', $container->parameters()->processValue('%lyssal_doctrine_extra.breadcrumbs.template%'))
        ->set(BreadcrumbGenerator::class)
        ->bind('$breadcrumbRoot', $container->parameters()->processValue('%lyssal_doctrine_extra.breadcrumbs.root%'))
    ;
};
