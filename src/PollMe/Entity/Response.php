<?php

namespace PollMe\Entity;


class Response
{
    protected $id;
    protected $survey_id;
    protected $title;
    protected $count = 0;


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
            $this->survey_id = $data['survey_id'];
        }

        if (isset($data['title'])) {
            $this->title = $data['title'];
        }

        if (isset($data['count'])) {
            $this->count = $data['count'];
        }
    }

    public function setId($id)
    {
        if ($this->id !== null) {
            throw new \RuntimeException('This survey already has an ID');
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

    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setCount($count)
    {
        $this->count = (int) $count;
        return $this;
    }

    public function getCount()
    {
        return $this->count;
    }
}
