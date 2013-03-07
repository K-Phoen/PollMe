<?php

namespace PollMe\Entity;


class User
{
    protected $id;
    protected $nickname;
    protected $password;


    public function __construct(array $data = array())
    {
        $this->loadFromArray($data);
    }

    public function loadFromArray(array $data = array())
    {
        if (isset($data['id'])) {
            $this->id = (int) $data['id'];
        }

        if (isset($data['nickname'])) {
            $this->nickname = $data['nickname'];
        }

        if (isset($data['password'])) {
            $this->password = $data['password'];
        }
    }

    public function setId($id)
    {
        if ($this->id !== null) {
            throw new \RuntimeException('This user already has an ID');
        }

        $this->id = $id;
        return $this;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setNickname($nickname)
    {
        $this->nickname = $nickname;
        return $this;
    }

    public function getNickname()
    {
        return $this->nickname;
    }

    public function setPassword($password)
    {
        $this->password = sha1($password);
        return $this;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function __toString()
    {
        return $this->nickname;
    }
}
