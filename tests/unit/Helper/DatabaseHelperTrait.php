<?php
declare(strict_types=1);

namespace App\Tests\Unit\Helper;

use Doctrine\ORM\Tools\SchemaTool;

trait DatabaseHelperTrait
{
    protected SchemaTool $schemaTool;

    public function setupDbSchemas($kernelClient): void
    {
        $entityManager = $kernelClient->getContainer()
            ->get('doctrine')
            ->getManager();

        $this->schemaTool = new SchemaTool($entityManager);
        $metadata = $entityManager->getMetadataFactory()->getAllMetadata();

        if (!empty($metadata)) {
            $this->schemaTool->dropSchema($metadata);
            $this->schemaTool->createSchema($metadata);
        }
    }

    public function dropDatabase(): void 
    {
        $this->schemaTool->dropDatabase();
    }
}
