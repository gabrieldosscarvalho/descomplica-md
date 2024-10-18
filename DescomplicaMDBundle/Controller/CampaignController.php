<?php

namespace MauticPlugin\DescomplicaMDBundle\Controller;

use Mautic\CoreBundle\Controller\AbstractFormController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use MauticPlugin\DescomplicaMDBundle\Helper\ChatGptHelper;

class CampaignController extends AbstractFormController
{
    private $chatGptHelper;

    public function __construct(ChatGptHelper $chatGptHelper)
    {
        $this->chatGptHelper = $chatGptHelper;
    }

    public function indexAction(Request $request)
    {
        $campaigns = $this->getCampaigns(); // Método fictício para obter campanhas
        $apiKey = $this->chatGptHelper->getApiKey(); // Obter a chave da API atual

        return $this->delegateView([
            'viewParameters' => [
                'pluginName' => 'DescomplicaMD',
                'campaigns' => $campaigns,
                'apiKey' => $apiKey, // Passar a chave da API para a view
            ],
            'contentTemplate' => 'DescomplicaMDBundle:Campaign:index.html.php',
        ]);
    }

    public function updateTokenAction(Request $request)
    {
        $newToken = $request->request->get('api_key');
        $this->chatGptHelper->setApiKey($newToken);

        return new RedirectResponse($this->generateUrl('plugin_descomplicamd_index'));
    }

    public function campaignAction(Request $request, $campaignId)
    {
        $action = $request->request->get('action');
        $campaign = $this->getCampaignById($campaignId);
        $campaignData = $this->getCampaignData($campaign);

        switch ($action) {
            case 'analyze':
                $result = $this->chatGptHelper->analyzeCampaigns($campaignData);
                break;
            case 'predict':
                $result = $this->chatGptHelper->generatePredictions($campaignData);
                break;
            case 'optimize':
                $result = $this->chatGptHelper->getOptimizationSuggestions($campaignData);
                break;
            case 'suggest':
                $result = $this->chatGptHelper->getSuggestions($campaignData);
                break;
            default:
                throw new \InvalidArgumentException("Ação inválida: $action");
        }

        return new JsonResponse($result);
    }

    public function chatAction(Request $request)
    {
        $campaignId = $request->request->get('campaignId');
        $campaign = $this->getCampaignById($campaignId);
        $campaignData = $this->getCampaignData($campaign);
        $userMessage = $request->request->get('message');
        $action = $request->request->get('action');

        $result = null;
        $resultAction = null;

        if ($action) {
            switch ($action) {
                case 'analyze':
                    $resultAction = $this->chatGptHelper->analyzeCampaigns($campaignData);
                    break;
                case 'predict':
                    $resultAction = $this->chatGptHelper->generatePredictions($campaignData);
                    break;
                case 'optimize':
                    $resultAction = $this->chatGptHelper->getOptimizationSuggestions($campaignData);
                    break;
                case 'suggest':
                    $resultAction = $this->chatGptHelper->getSuggestions($campaignData);
                    break;
                default:
                    throw new \InvalidArgumentException("Ação inválida: $action");
            }

            $result = $resultAction;
        }

        if ($userMessage) {
            if ($resultAction) {
                $result = $this->chatGptHelper->chat(
                    sprintf(
                        "resultado da ação %s: %s\nmensagem: %s\n",
                        $action,
                        $resultAction['choices'][0]['message']['content'],
                        $userMessage
                    ),
                    $campaign
                );
            } else {
                $result = $this->chatGptHelper->chat($userMessage, $campaign);
            }
        }

        return new JsonResponse($result);
    }

    private function getCampaigns()
    {
        // Lógica fictícia para obter campanhas
        return [
            ['id' => 1, 'name' => 'Um natal inesquecível'],
            ['id' => 2, 'name' => 'Volta às aulas'],
            // Adicionar mais campanhas conforme necessário
        ];
    }

    private function getCampaignById($campaignId)
    {
        // Lógica fictícia para obter uma campanha por ID
        $campaigns = $this->getCampaigns();
        foreach ($campaigns as $campaign) {
            if ($campaign['id'] == $campaignId) {
                return $campaign;
            }
        }
        return null;
    }

    private function getCampaignData($campaign)
    {
        // Dados fictícios para as campanhas
        $data = [
            1 => [
                'name' => 'Um natal inesquecível',
                'open_rate' => '25%',
                'click_rate' => '5%',
                'conversion_rate' => '2%',
                'roi' => '150%',
                'sales_increase' => '20%',
            ],
            2 => [
                'name' => 'Volta às aulas',
                'open_rate' => '30%',
                'click_rate' => '7%',
                'conversion_rate' => '3%',
                'roi' => '200%',
                'sales_increase' => '25%',
            ]
        ];

        return $data[$campaign['id']] ?? null;
    }
}
