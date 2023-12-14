<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

$configurator = Rally\Bootstrap::boot();
$container = $configurator->createContainer();
$application = $container->getByType(Nette\Application\Application::class);
$application->run();
