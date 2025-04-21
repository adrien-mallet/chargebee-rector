<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\EnvironmentToChargebeeClienRector;

use ChargebeeRector\Utils\Rector\Rector\EnvironmentToChargebeeClienRector;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

#[CoversClass(EnvironmentToChargebeeClienRector::class)]
final class EnvironmentToChargebeeClienRectorTest extends AbstractRectorTestCase
{
    #[DataProvider('provideData')]
    public function test(string $filePath): void
    {
        $this->doTestFile($filePath);
    }

    public static function provideData(): \Iterator
    {
        return self::yieldFilesFromDirectory(__DIR__ . '/Fixture');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/configured_rule.php';
    }
}
