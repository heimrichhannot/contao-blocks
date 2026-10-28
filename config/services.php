<?php

declare(strict_types=1);

use HeimrichHannot\Blocks\Controller\Backend\Content;
use HeimrichHannot\Blocks\EventListener\EntityFinderFindEventListener;
use HeimrichHannot\UtilsBundle\EntityFinder\EntityFinderHelper;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use function Symfony\Component\DependencyInjection\Loader\Configurator\param;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $container): void {
    $services = $container->services()
        ->defaults()
        ->autowire()
        ->autoconfigure();

    $services->load('HeimrichHannot\\Blocks\\', '../src/{ContentElement,Controller,DataContainer,EventListener,Exception,Model,Module}/*');

    $services->load('HeimrichHannot\\Blocks\\ContentElement\\', '../src/ContentElement/*')
        ->autowire(false);

    $services->load('HeimrichHannot\\Blocks\\DataContainer\\', '../src/DataContainer/*')
        ->public()
        ->bind('$kernelBundles', param('kernel.bundles'));

    $services->load('HeimrichHannot\\Blocks\\EventListener\\', '../src/EventListener/*')
        ->public();

    $services->set(EntityFinderFindEventListener::class)
        ->args([service(EntityFinderHelper::class)->nullOnInvalid()]);

    $services->set(Content::class)
        ->public();
};
