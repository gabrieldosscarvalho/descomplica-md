<?php

declare(strict_types=1);

namespace MauticPlugin\DescomplicaMDBundle\EventListener;

use Mautic\PluginBundle\Event\PluginInstallEvent;
use Mautic\PluginBundle\Event\PluginUpdateEvent;
use Mautic\PluginBundle\PluginEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class InstallUpdateSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            PluginEvents::ON_PLUGIN_INSTALL => ['onPluginInstall', 0],
            PluginEvents::ON_PLUGIN_UPDATE  => ['onPluginUpdate', 0],
        ];
    }

    public function onPluginInstall(PluginInstallEvent $event)
    {
        // Handle your logic here
    }

    public function onPluginUpdate(PluginUpdateEvent $event)
    {
        // Handle your logic here
    }
}
