<?php

declare(strict_types=1);

return [
    'name'        => 'DescomplicaMDBundle',
    'description' => 'DescomplicaMD plugin',
    'author'      => 'DescomplicaMD',
    'version'     => '1.0.0',
    'routes'      => [
        'main' => [
            'plugin_descomplicamd_index' => [
                'path'       => '/descomplicamd',
                'controller' => 'MauticPlugin\DescomplicaMDBundle\Controller\CampaignController::indexAction',
            ],
            'plugin_descomplicamd_update_token' => [
                'path'       => '/descomplicamd/update-token',
                'controller' => 'MauticPlugin\DescomplicaMDBundle\Controller\CampaignController::updateTokenAction',
            ],
            'plugin_descomplicamd_campaign_action' => [
                'path'       => '/descomplicamd/campaign/{campaignId}/action',
                'controller' => 'MauticPlugin\DescomplicaMDBundle\Controller\CampaignController::campaignAction',
            ],
            'plugin_descomplicamd_chat' => [
                'path'       => '/descomplicamd/chat',
                'controller' => 'MauticPlugin\DescomplicaMDBundle\Controller\CampaignController::chatAction',
            ],
        ],
    ],
    'services' => [
        'controllers' => [
            'mautic.controller.descomplicamd' => [
                'class'     => \MauticPlugin\DescomplicaMDBundle\Controller\DefaultController::class,
                'arguments' => 'mautic.helper.core_parameters',
            ],
            'mautic.controller.campaign' => [
                'class'     => \MauticPlugin\DescomplicaMDBundle\Controller\CampaignController::class,
                'arguments' => [
                    'mautic.plugin.descomplicamd.helper.chatgpt',
                ],
            ],
        ],
        'models'  => [
            'mautic.plugin.descomplicamd.model.apikey' => [
                'class' => \MauticPlugin\DescomplicaMDBundle\Model\ApiKeyModel::class,
                'arguments' => ['doctrine.orm.entity_manager'],
            ],
        ],
        'events' => [
            'mautic.plugin.descomplicamd.subscriber' => [
                'class'     => \MauticPlugin\DescomplicaMDBundle\EventListener\DescomplicaMDSubscriber::class,
                'arguments' => [
                    'mautic.plugin.descomplicamd.helper.campaign_analyzer',
                ],
            ],
        ],
        'forms' => [
            'mautic.plugin.descomplicamd.form.type' => [
                'class'     => \MauticPlugin\DescomplicaMDBundle\Form\Type\DescomplicaMDType::class,
                'arguments' => 'mautic.helper.core_parameters',
            ],
        ],
        'other' => [
            'mautic.plugin.descomplicamd.helper.chatgpt' => [
                'class'     => \MauticPlugin\DescomplicaMDBundle\Helper\ChatGptHelper::class,
                'arguments' => ['mautic.plugin.descomplicamd.model.apikey'],
            ],
            'mautic.plugin.descomplicamd.helper.campaign_analyzer' => [
                'class'     => \MauticPlugin\DescomplicaMDBundle\Helper\CampaignAnalyzer::class,
                'arguments' => ['mautic.plugin.descomplicamd.helper.chatgpt'],
            ],
        ],
    ],
    'menu' => [
        'main' => [
            'priority' => 1,
            'items' => [
                'plugin.descomplicamd.menu.index' => [
                    'route' => 'plugin_descomplicamd_index',
                    'label' => 'DescomplicaMD',
                    'iconClass' => 'fa-puzzle-piece',
                ],
            ],
        ],
    ],
    'parameters' => [
        'api_key' => [
            'label' => 'API Key',
            'type' => 'text',
            'required' => true,
            'default' => '',
        ],
    ],
];
