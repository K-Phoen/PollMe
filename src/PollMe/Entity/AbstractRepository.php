<?php

namespace PollMe\Entity;


abstract class AbstractRepository
{
    protected $pdo;


    abstract protected function hydrate($data);


    public function __construct(\Pdo $pdo)
    {
        $this->pdo = $pdo;
    }

    protected function hydrateSingle($stmt)
    {
        $row = $stmt->fetch(\Pdo::FETCH_ASSOC);
        return $row === false ? null : $this->hydrate($row);
    }

    protected function hydrateList($stmt)
    {
        $items = array();
        foreach ($stmt->fetchAll(\Pdo::FETCH_ASSOC) as $row) {
            $items[] = $this->hydrate($row);
        }
        return $items;
    }
}
