<?php

declare(strict_types=1);

namespace Shoper\Recruitment\Task\Entity\DatabaseHandler;

use Shoper\Recruitment\Task\Constants\ApiConstants;
use Shoper\Recruitment\Task\Model\DatabaseInterface;
use Shoper\Recruitment\Task\Request\ApiRequest;

/**
* Kalsa opisuje ustawienia połączenia z bazą danych 
*/
class Database
{
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
        $this->username = getenv("DB_USER");
        $this->password = getenv("DB_PASSWORD");
        $this->name = getenv("DB_NAME");

        $this->connect();
    }

    public function __destruct() {
        $this->disconnect();
    }

    public function insert(object $entity): void
    {
        $columns = '';
        $values = '';
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

        if($this->mysqli->errno === 1062) {
            throw new \Exception('Conflict error', ApiConstants::HTTP_CONFLICT);
        }
        $statement->close();
    }

    public function select(string $table, string $columns, array $conditions = [], int $limit = null, int $offset = null)
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

    private function connect(): void
    {
        $this->mysqli = new \mysqli($this->host, $this->username, $this->password, $this->name);

        if ($this->mysqli->connect_error) {
            throw new \Exception('Databse connection error', ApiConstants::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    private function disconnect()
    {
        if (isset($this->mysqli)) {
            $this->mysqli->close();
        }
    }

    private function fetchResponseData(\mysqli_stmt $statement): ?array
    {
        $metaData = $statement->result_metadata();

        while ($field = $metaData->fetch_field()) {
            $fields[$field->name] = &${$field->name};
        }
    
        call_user_func_array([$statement,'bind_result'],$fields);

        $iterator = 0;
        while ($statement->fetch()) {
            foreach($fields as $key => $value) {
                $results[$iterator][$key] = $value;
            }
            $iterator++;
        }
        return $results ?? null;
    }

    /**
     * @param array $arrayValues dane w formacie [[collumnName, value, (optional) operation, (optional) typ danej metody bind_param] ... []]
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
     * @param array $arrayValues dane w formacie [[collumnName, value, (optional) operation, (optional) type danej metody bind_param] ... []]
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