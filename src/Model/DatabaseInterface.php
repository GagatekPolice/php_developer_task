<?php

declare(strict_types=1);

namespace Shoper\Recruitment\Task\Model;;

interface DatabaseInterface {
function connect(): bool;

function delete(string $tableName, array $conditions);

function disconnect(): void;

function insert(string $tableName, array $columns, array $values);

function select(string $tableName, string $columns, array $conditions, int $limit, int $offset);

function update(string $tableName, array $columns, array $values, array $conditions);
}