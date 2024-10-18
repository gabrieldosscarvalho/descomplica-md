<?php

namespace MauticPlugin\DescomplicaMDBundle\Helper;

class CampaignAnalyzer
{
    private $chatGptHelper;

    public function __construct(ChatGptHelper $chatGptHelper)
    {
        $this->chatGptHelper = $chatGptHelper;
    }

    public function analyze(array $campaigns): array
    {
        $analysisResults = [];
        foreach ($campaigns as $campaign) {
            $analysis = $this->chatGptHelper->analyzeCampaigns($campaign);
            $analysisResults[$campaign['id']] = $analysis;
        }
        return $analysisResults;
    }

    public function predict(array $campaigns): array
    {
        $predictionResults = [];
        foreach ($campaigns as $campaign) {
            $prediction = $this->chatGptHelper->generatePredictions($campaign);
            $predictionResults[$campaign['id']] = $prediction;
        }
        return $predictionResults;
    }

    public function optimize(array $campaigns): array
    {
        $optimizationResults = [];
        foreach ($campaigns as $campaign) {
            $optimization = $this->chatGptHelper->getOptimizationSuggestions($campaign);
            $optimizationResults[$campaign['id']] = $optimization;
        }
        return $optimizationResults;
    }
}
