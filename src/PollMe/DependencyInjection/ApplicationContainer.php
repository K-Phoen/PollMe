<?php

namespace PollMe\DependencyInjection;

use Rock\Core\DependencyInjection\ApplicationContainer as BaseContainer;

use PollMe\Database\DB;
use PollMe\Listener\CreateDatabaseListener;
use PollMe\Listener\UserListener;
use PollMe\Entity\UserRepository;


class ApplicationContainer extends BaseContainer
{
    protected function registerServices()
    {
        parent::registerServices();

        $this['db.pdo'] = $this->share(function($c) {
            $dsn = 'mysql:host='.$c['db.host'].';dbname='.$c['db.name'];

            $pdo = new \Pdo($dsn, $c['db.user'], $c['db.pass'], array(
                \PDO::MYSQL_ATTR_INIT_COMMAND   => 'SET NAMES UTF8',
                \PDO::ATTR_ERRMODE              => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_AUTOCOMMIT           => true,
            ));

            return $pdo;
        });

        $this['repository.user'] = $this->share(function($c) {
            return new UserRepository($c['db.pdo']);
        });
    }

    protected function registerListeners()
    {
        parent::registerListeners();

        $this['user.user_listener'] = function($c) {
            return new UserListener($c['repository.user']);
        };

        $this['db.create_listener'] = function($c) {
            return new CreateDatabaseListener($c['db.pdo']);
        };
    }
}