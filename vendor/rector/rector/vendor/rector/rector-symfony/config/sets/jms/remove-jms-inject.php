<?php

declare (strict_types=1);
namespace RectorPrefix20220418;

use Rector\Config\RectorConfig;
use Rector\Symfony\Rector\Property\JMSInjectPropertyToConstructorInjectionRector;
return static function (\Rector\Config\RectorConfig $rectorConfig) : void {
    $services = $rectorConfig->services();
    $services->set(\Rector\Symfony\Rector\Property\JMSInjectPropertyToConstructorInjectionRector::class);
};