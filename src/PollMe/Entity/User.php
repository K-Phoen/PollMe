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
}
