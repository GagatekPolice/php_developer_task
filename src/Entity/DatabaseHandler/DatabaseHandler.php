<?php

declare(strict_types=1);

namespace Shoper\Recruitment\Task\Entity\DatabaseHandler;

use Shoper\Recruitment\Task\Model\DatabaseInterface;
use Shoper\Recruitment\Task\Request\ApiRequest;

/**
* Klasa 
*/
class DatabaseHandler
{
    /**
     * @var Database
     */
    private $database;

    public function __construct()
    {
        $this->database = new Database();
    }

    function findAll(string $entity): ?array
    {
        $result = $this->database->select($entity::CLASS_NAME, '*');

        return $this->buildResponseObjects($entity, $result);
    } 

    function findById(string $entity, string $id): ?object
    {
        $result = $this->database->select($entity::CLASS_NAME, '*', [['id', $id]]);

        return $this->buildResponseObject($entity, $result);
    }

    function insert(object $entity): void
    {
        $this->database->insert($entity);
    }

    /**
     * @return object
     */
    private function buildResponseObject($entityClass, $result): ?object
    {
        if ($result){
            $entity = new $entityClass(...array_values($result[0]));
        }

        return $entity ?? null;
    } 

    /**
     * @return array
     */
    private function buildResponseObjects($entityClass, $result): ?array
    {
        if ($result){
            foreach ($result as $entityArguments) {
                $entitys[] = new $entityClass(...array_values($entityArguments));
            }
        }

        return $entitys ?? null;
    }
}