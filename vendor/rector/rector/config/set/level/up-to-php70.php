<?php

declare (strict_types=1);
namespace RectorPrefix20220418;

use Rector\Config\RectorConfig;
use Rector\Core\ValueObject\PhpVersion;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;
return static function (\Rector\Config\RectorConfig $rectorConfig) : void {
    $rectorConfig->import(\Rector\Set\ValueObject\SetList::PHP_70);
    $rectorConfig->import(\Rector\Set\ValueObject\LevelSetList::UP_TO_PHP_56);
    // parameter must be defined after import, to override impored param version
    $rectorConfig->phpVersion(\Rector\Core\ValueObject\PhpVersion::PHP_70);
};