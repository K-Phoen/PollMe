<?php

namespace PollMe\Entity;


class ResponseRepository extends AbstractRepository
{
    public function findById($id)
    {
        $sql = 'SELECT id, survey_id, title, count FROM responses WHERE id = ?';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(array($id));

        return $this->hydrateSingle($stmt);
    }

    public function findBySurveyId($id)
    {
        $sql = 'SELECT id, survey_id, title, count FROM responses WHERE survey_id = ?';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(array($id));

        return $this->hydrateList($stmt);
    }

    public function persist(Response $response)
    {
        if ($response->getId() === null) {
            $this->insert($response);
        } else {
            $this->update($response);
        }
    }

    public function deleteForSurvey(Survey $survey)
    {
        $sql = 'DELETE FROM responses WHERE survey_id = ?';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(array($survey->getId()));
    }

    protected function insert(Response $response)
    {
        $sql = 'INSERT INTO responses (survey_id, title, count) VALUES (?, ?, ?)';
        $stmt = $this->pdo->prepare($sql);

        $stmt->execute(array($response->getSurveyId(), $response->getTitle(), $response->getCount()));
        $response->setId($this->pdo->lastInsertId());
    }

    protected function update(Response $response)
    {
        $sql = 'UPDATE responses SET survey_id = ?, title = ?, count = ? WHERE id = ?';
        $stmt = $this->pdo->prepare($sql);

        $stmt->execute(array($response->getSurveyId(), $response->getTitle(), $response->getCount(), $response->getId()));
    }

    protected function hydrate($data)
    {
        return new Response($data);
    }
}
