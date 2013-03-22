<?php

namespace PollMe\Entity;


class CommentRepository extends AbstractRepository
{
    protected $user_repository;


    public function __construct(\Pdo $pdo, UserRepository $user_repository)
    {
        parent::__construct($pdo);
        $this->user_repository = $user_repository;
    }

    public function findBySurveyId($id)
    {
        $sql = 'SELECT id, survey_id, user_id, comment, created_at FROM comments WHERE survey_id = ? ORDER BY created_at ASC';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(array($id));

        return $this->hydrateList($stmt);
    }

    public function persist(Comment $comment)
    {
        if ($comment->getId() === null) {
            $this->insert($comment);
        } else {
            throw new \Exception('Not implemented');
        }
    }

    public function deleteForSurvey(Survey $survey)
    {
        $sql = 'DELETE FROM comments WHERE survey_id = ?';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(array($survey->getId()));
    }

    protected function insert(Comment $comment)
    {
        $sql = 'INSERT INTO comments (survey_id, user_id, comment, created_at) VALUES (?, ?, ?, ?)';
        $stmt = $this->pdo->prepare($sql);

        $stmt->execute(array($comment->getSurveyId(), $comment->getUserId(), $comment->getComment(), $comment->getCreatedAt()->format('Y-m-d H:i:s')));
        $comment->setId($this->pdo->lastInsertId());
    }

    protected function hydrate($data)
    {
        $comment = new Comment($data);
        $comment->setUser($this->user_repository->findById($comment->getUserId()));

        return $comment;
    }
}
