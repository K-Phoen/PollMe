<?php

namespace PollMe\Entity;


class ResponseRepository
{
    protected $pdo;


    public function __construct(\Pdo $pdo)
    {
        $this->pdo = $pdo;
    }

    public function findByByIdForSurveyId($id, $survey_id)
    {
        $sql = 'SELECT id, survey_id, title, count FROM responses WHERE survey_id = ? AND id = ?';
        $stmt = $this->pdo->prepare($sql);

        $stmt->execute(array($survey_id, $id));

        $row = $stmt->fetch(\Pdo::FETCH_ASSOC);
        return $row === false ? null : $this->hydrateResponse($row);
    }

    public function findBySurveyId($id)
    {
        $sql = 'SELECT id, survey_id, title, count FROM responses WHERE survey_id = ?';
        $stmt = $this->pdo->prepare($sql);

        $stmt->execute(array($id));

        $responses = array();
        foreach ($stmt->fetchAll(\Pdo::FETCH_ASSOC) as $row) {
            $responses[] = $this->hydrateResponse($row);
        }
        return $responses;
    }

    public function persist(Response $response)
    {
        if ($response->getId() === null) {
            $this->insert($response);
        } else {
            $this->update($response);
        }
    }

    protected function insert(response $response)
    {
        $sql = 'INSERT INTO responses (survey_id, title, count) VALUES (?, ?, ?)';
        $stmt = $this->pdo->prepare($sql);

        $stmt->execute(array($response->getSurveyId(), $response->getTitle(), $response->getCount()));
        $response->setId($this->pdo->lastInsertId());
    }

    protected function update(response $response)
    {
        $sql = 'UPDATE responses SET survey_id = ?, title = ?, count = ? WHERE id = ?';
        $stmt = $this->pdo->prepare($sql);

        $stmt->execute(array($response->getSurveyId(), $response->getTitle(), $response->getCount(), $response->getId()));
    }

    protected function hydrateResponse($data)
    {
        return new Response($data);
    }
}
