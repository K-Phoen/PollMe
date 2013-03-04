<?php

namespace PollMe\Entity;


class UserRepository
{
    protected $pdo;


    public function __construct(\Pdo $pdo)
    {
        $this->pdo = $pdo;
    }

    public function findById($id)
    {
        $sql = 'SELECT id, nickname, password FROM users WHERE id = ?';
        $stmt = $this->pdo->prepare($sql);

        $stmt->execute(array($id));
        $row = $stmt->fetch(\Pdo::FETCH_ASSOC);

        return $row === false ? null : $this->hydrateUser($row);
    }

    protected function hydrateUser($data)
    {
        return new User($data);
    }
}
