<?php

namespace PollMe\Entity;


class SurveyRepository extends AbstractRepository
{
    protected $response_repository;


    public function __construct(\Pdo $pdo, ResponseRepository $response_repository)
    {
        parent::__construct($pdo);
        $this->response_repository = $response_repository;
    }

    public function findById($id)
    {
        $sql = 'SELECT id, owner_id, question FROM surveys WHERE id = ?';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(array($id));

        return $this->hydrateSingle($stmt);
    }

    public function findBySearch($search)
    {
        $sql = 'SELECT id, owner_id, question FROM surveys WHERE question LIKE ?';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(array('%'.$search.'%'));

        return $this->hydrateList($stmt);
    }

    public function findByOwnerId($id)
    {
        $sql = 'SELECT id, owner_id, question FROM surveys WHERE owner_id = ?';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(array($id));

        return $this->hydrateList($stmt);
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

    protected function hydrate($data)
    {
        $survey = new Survey($data);
        $survey->setResponses($this->response_repository->findBySurveyId($survey->getId()));
        return $survey;
    }
}
