<?php

namespace PollMe\Listener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

use Rock\Core\ApplicationEvents;


class CreateDatabaseListener implements EventSubscriberInterface
{
    protected $pdo;


    public function __construct(\Pdo $pdo)
    {
        $this->pdo = $pdo;
    }

    public static function getSubscribedEvents()
    {
        return array(
            ApplicationEvents::BOOT => array(array('onApplicationBoot', 30)),
        );
    }

    public function onApplicationBoot()
    {
        $tables = array(
            'users', 'surveys', 'responses', 'comments'
        );
        foreach ($tables as $table) {
            $method = 'create' . ucfirst($table);
            $this->{$method}();
        }
    }

    protected function createUsers()
    {
        $sql = <<<EOF
CREATE TABLE IF NOT EXISTS users(
    id       INT         AUTO_INCREMENT PRIMARY KEY,
    nickname VARCHAR(20) NOT NULL,
    password VARCHAR(50) NOT NULL
);
EOF;
        $this->pdo->exec($sql);
        $this->pdo->exec(sprintf('INSERT INTO users (nickname, password) VALUES ("%s", "%s")', 'test', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3')); // test:test
    }

    protected function createSurveys()
    {
        $sql = <<<EOF
CREATE TABLE IF NOT EXISTS surveys(
    id       INT          AUTO_INCREMENT PRIMARY KEY,
    owner_id INT          NOT NULL REFERENCES users(id),
    question VARCHAR(255) NOT NULL
);
EOF;
        $this->pdo->exec($sql);
    }

    protected function createResponses()
    {
        $sql = <<<EOF
CREATE TABLE IF NOT EXISTS responses(
    id          INT          AUTO_INCREMENT PRIMARY KEY,
    survey_id   INT          NOT NULL REFERENCES surveys(id),
    title       VARCHAR(255) NOT NULL,
    count       INT          NOT NULL DEFAULT 0
);
EOF;
        $this->pdo->exec($sql);
    }

    protected function createComments()
    {
        $sql = <<<EOF
CREATE TABLE IF NOT EXISTS comments(
    id          INT         AUTO_INCREMENT PRIMARY KEY,
    survey_id   INT         NOT NULL REFERENCES surveys(id),
    user_id     INT         NOT NULL REFERENCES users(id),
    comment     TEXT        NOT NULL,
    created_at  DATETIME    NOT NULL
);
EOF;
        $this->pdo->exec($sql);
    }
}
