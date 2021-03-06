<?php

declare(strict_types=1);

namespace Shoper\Recruitment\Task\Entity\DatabaseHandler;

use Shoper\Recruitment\Task\Model\ProductInterface;

/**
* Klasa odpowiedzialna za zarządzanie łącznością i żądaniami aplikacji do bazy danych
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

    public function deleteById(ProductInterface $entity): void
    {
        $this->database->deleteById($entity);
    }

    public function findAll(string $entity): ?array
    {
        $result = $this->database->select($entity::CLASS_NAME, '*');

        return $this->buildResponseObjects($entity, $result);
    } 

    public function findById(string $entity, string $id): ?ProductInterface
    {
        $result = $this->database->select($entity::CLASS_NAME, '*', [['id', $id]]);

        return $this->buildResponseObject($entity, $result);
    }

    function insert(ProductInterface $entity): void
    {
        $this->database->insert($entity);
    }

    public function update(ProductInterface $entity): ProductInterface
    {
        $this->database->update($entity);

        return $entity;
    }

    private function buildResponseObject(string $entityClass, ?array $result): ?ProductInterface
    {
        if ($result){
            return new $entityClass(...array_values($result[0]));
        }

        return null;
    } 

    private function buildResponseObjects(string $entityClass, ?array $result): ?array
    {
        if ($result){
            foreach ($result as $entityArguments) {
                $entitys[] = new $entityClass(...array_values($entityArguments));
            }
        }

        return $entitys ?? null;
    }
}