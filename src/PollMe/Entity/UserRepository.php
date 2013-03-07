<?php

namespace PollMe\Entity;


class UserRepository
{
    protected $pdo;


    public function __construct(\Pdo $pdo)
    {
        $this->pdo = $pdo;
    }

    public function isNicknameAvailable($nickname)
    {
        $sql = 'SELECT COUNT(1) FROM users WHERE nickname = ?';
        $stmt = $this->pdo->prepare($sql);

        $stmt->execute(array($nickname));
        return (int) $stmt->fetchColumn(0) === 0;
    }

    public function findByCredentials($nickname, $password)
    {
        $sql = 'SELECT id, nickname, password FROM users WHERE nickname = ? AND password = ?';
        $stmt = $this->pdo->prepare($sql);

        $stmt->execute(array($nickname, sha1($password)));
        $row = $stmt->fetch(\Pdo::FETCH_ASSOC);

        return $row === false ? null : $this->hydrateUser($row);
    }

    public function findById($id)
    {
        $sql = 'SELECT id, nickname, password FROM users WHERE id = ?';
        $stmt = $this->pdo->prepare($sql);

        $stmt->execute(array($id));
        $row = $stmt->fetch(\Pdo::FETCH_ASSOC);

        return $row === false ? null : $this->hydrateUser($row);
    }

    public function persist(User $user)
    {
        if ($user->getId() === null) {
            $this->insert($user);
        } else {
            $this->update($user);
        }
    }

    protected function insert(User $user)
    {
        $sql = 'INSERT INTO users (nickname, password) VALUES (?, ?)';
        $stmt = $this->pdo->prepare($sql);

        $stmt->execute(array($user->getNickname(), $user->getPassword()));
        $user->setId($this->pdo->lastInsertId());
    }

    protected function update(User $user)
    {
        $sql = 'UPDATE users SET nickname = ?, password = ? WHERE id = ?';
        $stmt = $this->pdo->prepare($sql);

        $stmt->execute(array($user->getNickname(), $user->getPassword(), $user->getId()));
    }

    protected function hydrateUser($data)
    {
        return new User($data);
    }
}
