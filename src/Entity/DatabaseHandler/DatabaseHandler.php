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

    /**
     * @return array
     */
    function findAll(string $entity): ?array
    {
        $result = $this->database->select($entity::TABLE_NAME, '*');

        return $this->buildResponseObject($entity, $result);
    }

    // public function __destruct() {
    //     $this->database->disconnect();
    // }

    public function getDatabase()
    {
        return $this->database;
    }

private function buildResponseObject($entityClass, $result): ?array
{
    foreach ($result as $entityArguments) {
        foreach ($entityArguments as $field => $value) {
            $fields[] = $value;
        }
        $entitys[] = new $entityClass(...array_values($entityArguments));
    }

    return $entitys ?? null;
}
}