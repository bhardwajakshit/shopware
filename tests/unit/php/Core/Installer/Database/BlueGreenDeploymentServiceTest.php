<?php declare(strict_types=1);

namespace Shopware\Tests\Unit\Core\Installer\Database;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use PHPUnit\Framework\TestCase;
use Shopware\Core\DevOps\Environment\EnvironmentHelper;
use Shopware\Core\Framework\Test\TestCaseBase\EnvTestBehaviour;
use Shopware\Core\Installer\Database\BlueGreenDeploymentService;

/**
 * @internal
 * @covers \Shopware\Core\Installer\Database\BlueGreenDeploymentService
 */
class BlueGreenDeploymentServiceTest extends TestCase
{
    use EnvTestBehaviour;

    public function testSetsEnvironmentVariableToTrueIfTriggersCanBeCreated(): void
    {
        $this->setEnvVars([BlueGreenDeploymentService::ENV_NAME => '0']);

        $connection = $this->createMock(Connection::class);
        $connection->expects(static::exactly(3))->method('executeQuery');

        $service = new BlueGreenDeploymentService();
        $service->setEnvironmentVariable($connection);

        static::assertTrue($_ENV[BlueGreenDeploymentService::ENV_NAME]);
        static::assertTrue($_SERVER[BlueGreenDeploymentService::ENV_NAME]);
        static::assertTrue(EnvironmentHelper::getVariable(BlueGreenDeploymentService::ENV_NAME));
    }

    public function testSetsEnvironmentVariableToFalseIfTriggersCanNotBeCreated(): void
    {
        $this->setEnvVars([BlueGreenDeploymentService::ENV_NAME => '1']);

        $connection = $this->createMock(Connection::class);
        $connection->expects(static::exactly(3))
            ->method('executeQuery')
            ->willReturnOnConsecutiveCalls(
                null,
                static::throwException(new Exception()),
                null
            );

        $service = new BlueGreenDeploymentService();
        $service->setEnvironmentVariable($connection);

        static::assertFalse($_ENV[BlueGreenDeploymentService::ENV_NAME]);
        static::assertFalse($_SERVER[BlueGreenDeploymentService::ENV_NAME]);
        static::assertFalse(EnvironmentHelper::getVariable(BlueGreenDeploymentService::ENV_NAME));
    }
}
