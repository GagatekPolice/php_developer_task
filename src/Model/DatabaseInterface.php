<?php

declare(strict_types=1);

namespace Shoper\Recruitment\Task\Model;

interface DatabaseInterface {
public function deleteById(ProductInterface $entity): void;

public function insert(ProductInterface $entity): void;

public function select(string $table, string $columns, array $conditions = [], int $limit = null, int $offset = null): ?array;

public function update(ProductInterface $entity): void;
}