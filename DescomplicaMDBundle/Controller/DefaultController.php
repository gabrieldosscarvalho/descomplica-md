<?php

namespace MauticPlugin\DescomplicaMDBundle\Controller;

use Mautic\CoreBundle\Controller\AbstractFormController;

class DefaultController extends AbstractFormController
{
    public function indexAction()
    {
        return $this->delegateView([
            'viewParameters' => [
                'pluginName' => 'DescomplicaMD',
            ],
            'contentTemplate' => 'DescomplicaMDBundle:Default:index.html.php',
        ]);
    }
}
