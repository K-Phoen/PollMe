<?php

namespace PollMe\Entity;


class Comment
{
    protected $id;
    protected $survey_id;
    protected $user_id;
    protected $comment;
    protected $created_at;

    protected $user;


    public function __construct(array $data = array())
    {
        $this->loadFromArray($data);
    }

    public function loadFromArray(array $data = array())
    {
        if (isset($data['id'])) {
            $this->id = (int) $data['id'];
        }

        if (isset($data['survey_id'])) {
            $this->survey_id = (int) $data['survey_id'];
        }

        if (isset($data['user_id'])) {
            $this->user_id = (int) $data['user_id'];
        }

        if (isset($data['comment'])) {
            $this->comment = $data['comment'];
        }

        if (isset($data['created_at'])) {
            $this->created_at = $data['created_at'];
        }
    }

    public function setId($id)
    {
        if ($this->id !== null) {
            throw new \RuntimeException('This comment already has an ID');
        }

        $this->id = (int) $id;
        return $this;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setSurveyId($survey_id)
    {
        $this->survey_id = (int) $survey_id;
        return $this;
    }

    public function getSurveyId()
    {
        return $this->survey_id;
    }

    public function setUserId($user_id)
    {
        $this->user_id = (int) $user_id;
        return $this;
    }

    public function getUserId()
    {
        return $this->user_id;
    }

    public function setComment($comment)
    {
        $this->comment = $comment;
        return $this;
    }

    public function getComment()
    {
        return $this->comment;
    }

    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at instanceof \DateTime ? $created_at : new \DateTime($created_at);
        return $this;
    }

    public function getCreatedAt()
    {
        return $this->created_at;
    }

    public function setUser(User $user)
    {
        $this->user = $user;
        return $this;
    }

    public function getUser()
    {
        return $this->user;
    }
}
