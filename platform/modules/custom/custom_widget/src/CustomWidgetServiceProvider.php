<?php

namespace Drupal\custom_widget;

use Drupal\Core\DependencyInjection\ServiceProviderBase;
use Drupal\Core\DependencyInjection\ServiceProviderInterface;
use Drupal\Core\DependencyInjection\ContainerBuilder;

class CustomWidgetServiceProvider extends ServiceProviderBase implements ServiceProviderInterface {
    public function alter(ContainerBuilder $container) {
        $defination = $container->getDefinition('file_system');
        $defination->setClass('Drupal\custom_widget\MyFileSystem\MyFileSystem');
    }
}
