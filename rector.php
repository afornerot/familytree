<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Doctrine\Set\DoctrineSetList;
use Rector\Symfony\Set\SymfonySetList;

return RectorConfig::configure()
    ->withPaths([__DIR__ . '/src'])
    ->withSets([
        // Annotations Doctrine (@ORM\*) -> attributs PHP 8
        DoctrineSetList::ANNOTATIONS_TO_ATTRIBUTES,
        // Annotations Symfony -> attributs
        SymfonySetList::ANNOTATIONS_TO_ATTRIBUTES,
        // Règles déduites des versions installées (symfony/* 7.4, doctrine/orm 3.7)
        DoctrineSetList::COMPOSER_BASED,
        SymfonySetList::COMPOSER_BASED,
        SymfonySetList::SYMFONY_CODE_QUALITY,
    ]);
