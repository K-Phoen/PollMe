<?php

namespace PollMe\Entity;


class SurveyRepository
{
    protected $pdo;
    protected $response_repository;


    public function __construct(\Pdo $pdo, ResponseRepository $response_repository)
    {
        $this->pdo = $pdo;
        $this->response_repository = $response_repository;
    }

    public function findById($id)
    {
        $sql = 'SELECT id, owner_id, question FROM surveys WHERE id = ?';
        $stmt = $this->pdo->prepare($sql);

        $stmt->execute(array($id));
        $row = $stmt->fetch(\Pdo::FETCH_ASSOC);

        return $row === false ? null : $this->hydrateSurvey($row);
    }

    public function persist(Survey $survey)
    {
        if ($survey->getId() === null) {
            $this->insert($survey);
        } else {
            $this->update($survey);
        }
    }

    protected function insert(Survey $survey)
    {
        $sql = 'INSERT INTO surveys (owner_id, question) VALUES (?, ?)';
        $stmt = $this->pdo->prepare($sql);

        $stmt->execute(array($survey->getOwnerId(), $survey->getQuestion()));
        $survey->setId($this->pdo->lastInsertId());

        foreach ($survey->getResponses() as $response) {
            $this->response_repository->persist($response);
        }
    }

    protected function hydrateSurvey($data)
    {
        return new Survey($data);
    }
}