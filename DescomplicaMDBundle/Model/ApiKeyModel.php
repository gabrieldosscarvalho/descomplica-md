<?php

namespace MauticPlugin\DescomplicaMDBundle\Model;

use Mautic\CoreBundle\Model\AbstractCommonModel;
use MauticPlugin\DescomplicaMDBundle\Entity\ApiKey;
use Doctrine\ORM\EntityManager;

class ApiKeyModel extends AbstractCommonModel
{
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getRepository()
    {
        return $this->entityManager->getRepository(ApiKey::class);
    }

    public function saveApiKey(string $apiKey)
    {
        $apiKeyEntity = new ApiKey();
        $apiKeyEntity->setKey($apiKey);
        $this->entityManager->persist($apiKeyEntity);
        $this->entityManager->flush();
    }

    public function getLatestApiKey(): ?ApiKey
    {
        return $this->getRepository()->findOneBy([], ['id' => 'DESC']);
    }
}
