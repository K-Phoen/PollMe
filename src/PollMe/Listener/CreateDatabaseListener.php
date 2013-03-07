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
        $tables = $this->tablesExist(array(
            'users', 'surveys', 'responses', 'comments'
        ));

        foreach ($tables as $table => $exist) {
            if (!$exist) {
                $method = 'create' . ucfirst($table);
                $this->{$method}();
            }
        }
    }

    protected function tablesExist($names)
    {
        $results = array();

        $sql = 'SELECT COUNT(1) as result FROM Information_schema.tables WHERE table_name = ?';
        $stmt = $this->pdo->prepare($sql);

        foreach ($names as $name) {
            $stmt->execute(array($name));
            $results[$name] = (int) $stmt->fetchColumn(0) === 1;
        }

        return $results;
    }

    protected function createUsers()
    {
        $sql = <<<EOF
CREATE TABLE users(
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
CREATE TABLE surveys(
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
CREATE TABLE responses(
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
CREATE TABLE comments(
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
