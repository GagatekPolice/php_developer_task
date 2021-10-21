<?php

declare(strict_types=1);

namespace Shoper\Recruitment\Task\Entity\DatabaseHandler;

use Shoper\Recruitment\Task\Constants\ApiConstants;
use Shoper\Recruitment\Task\Model\DatabaseInterface;
use Shoper\Recruitment\Task\Model\ProductInterface;

/**
* Klasa opisuje ustawienia połączenia z bazą danych oraz dozwolone operacje na niej
*/
class Database implements DatabaseInterface
{
    const CONFLICT_CODE = 1062;
    const DATABASE_PORT = 3306;

    /**
     * @var string
     */
    private $host;

    /**
     * @var \mysqli
     */
    private $mysqli;   

    /**
     * @var string
     */
    private $name;
    
    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    private $username;
    
    public function __construct()
    {
        $this->host = getenv("DB_HOST");
        $this->username = getenv("MYSQL_USER");
        $this->password = getenv("MYSQL_PASSWORD");
        $this->name = getenv("MYSQL_DATABASE");

        $this->connect();
    }

    public function __destruct() {
        $this->disconnect();
    }

    /**
    * Metoda odpowiedzialna za usunięcie obiektu encji o zadanym id z bazy danych
    */
    public function deleteById(ProductInterface $entity): void
    {
        $statement = $this->mysqli->prepare("DELETE FROM " . $entity::CLASS_NAME . " WHERE id = ( ? )");

        if (!$statement) {
            throw new \Exception('Databse statement error', ApiConstants::HTTP_INTERNAL_SERVER_ERROR);
        }

        // Zmienna stworzona na potrzeby bind_param, który nie przyjmuje referencji
        $productId = $entity->getId();
        $statement->bind_param('s', $productId);

        $statement->execute();

        if ($this->mysqli->errno) {
            throw new \Exception('Deleting row error', ApiConstants::HTTP_CONFLICT);
        }

        $statement->close();
    }

    /**
    * Metoda odpowiedzialna za dodanie obiektu encji do bazy danych
    */
    public function insert(ProductInterface $entity): void
    {
        $columns = '';
        $values = '';;
        foreach ($entity->getObjectVars() as $name => $value) {
            $columns .= $columns
                ? ', ' . $name 
                : $name
            ;
            $values .= $values
                ? ', ?' 
                : '?'
            ;
            $conditions[] = [$name, $value];
        };

        $statement = $this->mysqli->prepare("INSERT INTO " . $entity::CLASS_NAME . " (${columns}) VALUES (${values})");

        if (!$statement) {
            throw new \Exception('Databse statement error', ApiConstants::HTTP_INTERNAL_SERVER_ERROR);
        }

        $this->putBindParamValues($conditions, $statement);

        $statement->execute();

        if ($this->mysqli->errno === self::CONFLICT_CODE) {
            throw new \Exception('Conflict error', ApiConstants::HTTP_CONFLICT);
        }

        $statement->close();
    }

    /**
    * Metoda odpowiedzialna za spreparowanie zapytania i wykonanie go na podstawie przekazanych parametrów
    */
    public function select(string $table, string $columns, array $conditions = [], int $limit = null, int $offset = null): ?array
    {
        $query = "SELECT ${columns} FROM ${table}";

        if (!empty($conditions)) {
            $query .= " WHERE " . $this->generateWhereString($conditions);
        }

        if (isset($limit) && isset($offset)) {
            $query .= "LIMIT ${limit} OFFSET ${offset}";
        } else if (isset($limit) && !isset($offset))
        {
            $query .= "LIMIT ${limit}";
        }

        $statement = $this->mysqli->prepare($query);

        if (!$statement) {
            throw new \Exception('Databse statement error', ApiConstants::HTTP_INTERNAL_SERVER_ERROR);
        }

        $this->putBindParamValues($conditions, $statement);
        $statement->execute();
        $results = $this->fetchResponseData($statement);
        $statement->close();

        return $results ?? null;
    }

    /**
    * Metoda odpowiedzialna za aktualizację obiektu encji w bazie danych
    */
    public function update(ProductInterface $entity): void
    {
        $query = "UPDATE " . $entity::CLASS_NAME . " SET ";

        $columnsQuery = '';
        foreach ($entity->asJson() as $fieldName => $value) {
            if($fieldName !== 'type' && $fieldName !== 'id') {
                if(empty($columnsQuery)) {
                    $columnsQuery .= $fieldName . ' = ?';
                } else {
                    $columnsQuery .= ' , ' . $fieldName . ' = ?';
                }
                $conditions[] = [$fieldName, $value];
            }
        }

        $conditions[] = ['id', $entity->getId()];
        $statement = $this->mysqli->prepare($query . $columnsQuery . " WHERE id = ?");

        if (!$statement) {
            throw new \Exception('Databse statement error', ApiConstants::HTTP_INTERNAL_SERVER_ERROR);
        }

        $this->putBindParamValues($conditions, $statement);

        $statement->execute();

        if ($statement->error) {
            throw new \Exception('Databse insert error', ApiConstants::HTTP_INTERNAL_SERVER_ERROR);
        }

        $statement->close();
    }

    private function connect(): void
    {
        $this->mysqli = new \mysqli($this->host, $this->username, $this->password, $this->name);
        if ($this->mysqli->connect_error) {
            throw new \Exception('Databse connection error', ApiConstants::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    private function disconnect(): void
    {
        if (isset($this->mysqli)) {
            $this->mysqli->close();
        }
    }

    /**
    * Metoda odpowiedzialna za pobranie i sformatowanie odpowiedzi na zapytanie
    */
    private function fetchResponseData(\mysqli_stmt $statement): ?array
    {
        $metaData = $statement->result_metadata();
        while ($field = $metaData->fetch_field()) {
            $fields[$field->name] = &${$field->name};
        }
    
        call_user_func_array([$statement,'bind_result'],$fields);

        $iterator = 0;
        ksort($fields);

        while ($statement->fetch()) {
            foreach($fields as $key => $value) {
                $results[$iterator][$key] = $value;
            }
            $iterator++;
        }

        return $results ?? null;
    }

    /**
     * @param array $arrayValues dane w formacie
     * [[collumnName, value, (optional) operation, (optional) typ danej metody bind_param] ... []]
     * @example [['id', '8fe8e007*']['city', 'Szczecin', 'AND', 's']]
     */
    private function generateWhereString(array $conditions): string
    {
        $buildString = "";
        foreach ($conditions as $condition) {
            $buildString .= $buildString
                ? ($condition[2] ?? 'AND ') . $condition[1] . " = ? "
                : $condition[0] . " = ? ";
        }

        return $buildString;
    }

    /**
     * @param array $arrayValues dane w formacie
     * [[collumnName, value, (optional) operation, (optional) type danej metody bind_param] ... []]
     * @example [['id', '8fe8e007*']['city', 'Szczecin', 'AND', 's']]
     */
    private function putBindParamValues(array $conditions, \mysqli_stmt $statement): void
    {
        $bindParamValues = ['types'=> '', 'values'=> []];
        foreach ($conditions as $condition) {
            $bindParamValues['values'][] = $condition[1];
            $bindParamValues['types'] .= $condition[3] ?? 's';
        }

        if ($bindParamValues['values'] && count($bindParamValues['values']) > 1) {
            $statement->bind_param($bindParamValues['types'], ...$bindParamValues['values']);
        } else if ($bindParamValues['values']){
            $statement->bind_param($bindParamValues['types'], $bindParamValues['values'][0]);
        }
    }
}