<?php

require_once 'autoload.php';

use Symfony\Component\Yaml\Yaml;

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
        return array_merge(parent::getContainerParameters(), $this->getConfigParameters());
    }

    protected function getConfigParameters()
    {
        $params = array();
        foreach (Yaml::parse($this->getConfigDir() . '/config.yml') as $key => $value) {
            $params[$key] = $value;
        }

        // override DB parameters
        $map = array(
            'dbHost'    => 'db.host',
            'dbBd'      => 'db.name',
            'dbLogin'   => 'db.user',
            'dbPass'    => 'db.pass',
        );
        foreach (array_keys($map) as $key) {
            if (!empty($_SERVER[$key])) {
                $params[$map[$key]] = $_SERVER[$key];
            }
        }

        return $params;
    }

    protected function registerEvents()
    {
        parent::registerEvents();

        $this->container['event.dispatcher']->addSubscriber($this->container['user.user_listener']);
        $this->container['event.dispatcher']->addSubscriber($this->container['db.create_listener']);
    }
}
