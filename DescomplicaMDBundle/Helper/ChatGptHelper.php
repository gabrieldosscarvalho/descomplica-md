<?php

namespace MauticPlugin\DescomplicaMDBundle\Helper;

use GuzzleHttp\Client;
use MauticPlugin\DescomplicaMDBundle\Model\ApiKeyModel;

class ChatGptHelper
{
    private $apiKey;
    private $client;
    private $apiKeyModel;

    public function __construct(ApiKeyModel $apiKeyModel)
    {
        $this->apiKeyModel = $apiKeyModel;
        $this->loadApiKey();
        $this->updateClient();
    }

    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;
        $this->updateClient();
        $this->apiKeyModel->saveApiKey($apiKey);
    }

    public function getApiKey()
    {
        return $this->apiKey;
    }

    private function loadApiKey()
    {
        $apiKeyEntity = $this->apiKeyModel->getLatestApiKey();
        if ($apiKeyEntity) {
            $this->apiKey = $apiKeyEntity->getKey();
        }
    }

    private function updateClient()
    {
        $this->client = new Client([
            'base_uri' => 'https://api.openai.com/v1/',
            'headers' => [
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type'  => 'application/json',
            ],
        ]);
    }

    public function analyzeCampaigns($campaignData)
    {
        return $this->callChatGptApi('analyze', $campaignData);
    }

    public function generatePredictions($campaignData)
    {
        return $this->callChatGptApi('predict', $campaignData);
    }

    public function getOptimizationSuggestions($campaignData)
    {
        return $this->callChatGptApi('optimize', $campaignData);
    }

    public function getSuggestions($campaignData)
    {
        return $this->callChatGptApi('suggest', $campaignData);
    }

    public function chat($userMessage, $campaign)
    {
        return $this->callChatGptApi('chat', ['message' => $userMessage, 'campaign' => $campaign]);
    }

    private function callChatGptApi($endpoint, $data)
    {
        $response = $this->client->post('chat/completions', [ // Atualize o endpoint aqui
            'json' => [
                'model' => 'gpt-3.5-turbo', // Modelo atualizado
                'messages' => [
                    ['role' => 'system', 'content' => 'Você é um assistente útil e todas as conversas devem ocorrer em português.'],
                    ['role' => 'user', 'content' => $this->generatePrompt($endpoint, $data)],
                ],
                'max_tokens' => 1500,
            ],
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }

    private function generatePrompt($endpoint, $data)
    {
        switch ($endpoint) {
            case 'analyze':
                return "Exiba as informações de entrada fornecidas e Analise os seguintes dados da campanha e forneça insights:\n" . json_encode($data);
            case 'predict':
                return "Exiba as informações de entrada fornecidas e Preveja o desempenho futuro dos seguintes dados da campanha:\n" . json_encode($data);
            case 'optimize':
                return "Exiba as informações de entrada fornecidas e Forneça sugestões de otimização para os seguintes dados da campanha:\n" . json_encode($data);
            case 'suggest':
                return "Exiba as informações de entrada fornecidas e Forneça sugestões para os seguintes dados da campanha:\n" . json_encode($data);
            case 'chat':
                return "Campanha: " . json_encode($data['campaign']) . "\nMensagem: " . $data['message'];
            default:
                throw new \InvalidArgumentException("Endpoint inválido: $endpoint");
        }
    }
}
