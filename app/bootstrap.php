<?php

require_once 'autoload.php';

use Rock\Core\ApplicationKernel as BaseApplicationKernel;
use PollMe\DependencyInjection\ApplicationContainer;


class ApplicationKernel extends BaseApplicationKernel
{
    public function getTemplatesDir()
    {
        return __DIR__ . '/../src/PollMe/Resources/views';
    }

    public function getCacheDir()
    {
        return __DIR__ . '/cache';
    }

    public function getConfigDir()
    {
      return __DIR__ . '/config';
    }

    protected function buildContainer()
    {
        $this->container = new ApplicationContainer($this->getContainerParameters());
    }

    protected function getContainerParameters()
    {
        return array_merge(parent::getContainerParameters(), array(
            'db.host' => 'localhost',
            'db.name' => 'poll_me',
            'db.user' => 'poll_me',
            'db.pass' => 'poll_me',
        ));
    }

    protected function registerEvents()
    {
        parent::registerEvents();

        $this->container['event.dispatcher']->addSubscriber($this->container['user.user_listener']);
        $this->container['event.dispatcher']->addSubscriber($this->container['db.create_listener']);
    }
}
