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

    public function disconnect()
    {
        if (isset($this->mysqli)) {
            $this->mysqli->close();
        }
    }

    function select(string $table, string $columns, array $conditions = [], string $aliases = null, int $limit = null, int $offset = null)
    {
        $query = "SELECT ${columns} FROM ${table}";

        // $query = $aliases
        //     ? $query . ' ' . $aliases . ''
        //     : $query;
        if (!empty($conditions)) {
            $query .= " WHERE " . $this->generateWhereString($conditions);
        }
        if (isset($limit) && isset($offset)) {
            $query .= "LIMIT ${limit} OFFSET ${offset}";
        } else if (isset($limit) && !isset($offset))
        {
            $query .= "LIMIT ${limit}";
        }

        // var_dump($query);

        $statement = $this->mysqli->prepare($query);

        if (!$statement) {
            var_dump($this->mysqli->connect_error);
            throw new \Exception('Databse statement error', ApiConstants::HTTP_INTERNAL_SERVER_ERROR);
        }
        $bindParamValues = $this->generateBindParamValues($conditions);
    
        if ($bindParamValues['values']) {
            $statement->bind_param($bindParamValues['types'], ...$bindParamValues['values']);
        }

        $statement->execute();

        //ToDo: Edytować to
    // Get statement for field names
        $stmt = $statement;
        $meta = $stmt->result_metadata();

        // This is the tricky bit dynamically creating an array of variables to use
        // to bind the results
        while ($field = $meta->fetch_field()) {
            $var = $field->name; 
            ${$var} = null; 
            $fields[$var] = &${$var};
        }

        // Bind Results
        call_user_func_array([$stmt,'bind_result'],$fields);

        // Fetch Results
        $i = 0;
        while ($stmt->fetch()) {
            $results[$i] = [];
            foreach($fields as $k => $v)
                $results[$i][$k] = $v;
            $i++;
        }
        $statement->close();
    
        return $results;
    }

    private function connect(): void
    {
        $this->mysqli = new \mysqli($this->host, $this->username, $this->password, $this->name);

        if ($this->mysqli->connect_error) {
            throw new \Exception('Databse connection error', ApiConstants::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param array $arrayValues dane w formacie [[collumnName, value, (optional) operation, (optional) typ danej bind_param] ... []]
     * @return string
     */
    private function generateBindParamValues(array $conditions): array
    {
        $bindParamValues = ['types'=> [], 'values'=> []];
        foreach ($conditions as $condition) {
            $bindParamValues['values'][] = $condition[1];
            $bindParamValues['types'][] = $condition[2] ?? 's';
        }

        return $bindParamValues;
    }

    /**
     * @param array $arrayValues dane w formacie [[collumnName, value, (optional) operation, (optional) typ danej bind_param] ... []]
     * @return string
     */
    private function generateWhereString(array $conditions): string
    {
        $buildString = "";
        foreach ($conditions as $condition) {
            $buildString .= $buildString
                ? ($condition[2] ?? 'AND ') . $condition[1] . " = ? "
                : $condition[1] . " = ? ";
        }

        return $buildString;
    }
}