<?php

declare(strict_types=1);

use ChargebeeRector\Utils\Rector\Rector\EnvironmentToChargebeeClienRector;
use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\Name\RenameClassRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(
        EnvironmentToChargebeeClienRector::class
    );

    $rectorConfig->ruleWithConfiguration(RenameClassRector::class, [
        'ChargeBee\ChargeBee\Models\Environment' => 'Chargebee\ChargebeeClient',
    ]);
};
