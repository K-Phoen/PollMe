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
            'db.host' => !empty($_SERVER['dbHost']) ? $_SERVER['dbHost'] : 'localhost',
            'db.name' => !empty($_SERVER['dbBd']) ? $_SERVER['dbBd'] : 'poll_me',
            'db.user' => !empty($_SERVER['dbPass']) ? $_SERVER['dbPass'] :'poll_me',
            'db.pass' => !empty($_SERVER['dbLogin']) ? $_SERVER['dbLogin'] : 'poll_me',
        ));
    }

    protected function registerEvents()
    {
        parent::registerEvents();

        $this->container['event.dispatcher']->addSubscriber($this->container['user.user_listener']);
        $this->container['event.dispatcher']->addSubscriber($this->container['db.create_listener']);
    }
}
