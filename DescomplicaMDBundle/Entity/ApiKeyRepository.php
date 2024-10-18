<?php

namespace MauticPlugin\DescomplicaMDBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use Mautic\CoreBundle\Doctrine\Mapping\ClassMetadataBuilder;
use MauticPlugin\DescomplicaMDBundle\Entity\ApiKey;

class ApiKeyRepository extends EntityRepository
{
    public function findLatest(): ?ApiKey
    {
        return $this->findOneBy([], ['id' => 'DESC']);
    }

    public static function loadMetadata(ClassMetadata $metadata)
    {
        $builder = new ClassMetadataBuilder($metadata);
        $builder->setTable('plugin_descomplicamd_api_key')
            ->setCustomRepositoryClass(ApiKeyRepository::class)
            ->addId()
            ->addNamedField('key', 'string', 'key', true);
    }
}
