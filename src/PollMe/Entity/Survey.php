<?php

namespace PollMe\Entity;


class Survey
{
    protected $id;
    protected $owner_id;
    protected $question;

    protected $responses = array();
    protected $comments = array();


    public function __construct(array $data = array())
    {
        $this->loadFromArray($data);
    }

    public function loadFromArray(array $data = array())
    {
        if (isset($data['id'])) {
            $this->id = (int) $data['id'];
        }

        if (isset($data['owner_id'])) {
            $this->owner_id = (int) $data['owner_id'];
        }

        if (isset($data['question'])) {
            $this->question = $data['question'];
        }
    }

    public function setId($id)
    {
        if ($this->id !== null) {
            throw new \RuntimeException('This survey already has an ID');
        }

        $this->id = (int) $id;

        foreach ($this->getResponses() as $response) {
            $response->setSurveyId($this->getId());
        }

        return $this;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setOwnerId($owner_id)
    {
        $this->owner_id = $owner_id;
        return $this;
    }

    public function getOwnerId()
    {
        return $this->owner_id;
    }

    public function setQuestion($question)
    {
        $this->question = $question;
        return $this;
    }

    public function getQuestion()
    {
        return $this->question;
    }

    public function addResponse(Response $response)
    {
        $this->responses[] = $response;
        $response->setSurveyId($this->getId());
        return $this;
    }

    public function setResponses(array $responses)
    {
        foreach ($responses as $response) {
            $this->addResponse($response);
        }
        return $this;
    }

    public function getResponses()
    {
        return $this->responses;
    }

    public function addComment(Comment $comment)
    {
        $this->comments[] = $comment;
        $comment->setSurveyId($this->getId());
        return $this;
    }

    public function setComments(array $comments)
    {
        foreach ($comments as $comment) {
            $this->addComment($comment);
        }
        return $this;
    }

    public function getComments()
    {
        return $this->comments;
    }

    public function computePercentages()
    {
        $nb_votes = 0;
        foreach ($this->getResponses() as $response) {
            $nb_votes += $response->getCount();
        }

        if ($nb_votes === 0) {
            return $this;
        }

        foreach ($this->getResponses() as $response) {
            $response->setPercentage(100 * $response->getCount() / $nb_votes);
        }

        return $this;
    }
}
