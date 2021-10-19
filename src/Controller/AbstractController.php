<?php

declare(strict_types=1);

namespace Shoper\Recruitment\Task\Controller;

use Shoper\Recruitment\Task\Constants\ApiConstants;
use Shoper\Recruitment\Task\Entity\DatabaseHandler\DatabaseHandler;
use Shoper\Recruitment\Task\Model\ProductInterface;

abstract class AbstractController
{
    /**
    * @var DatabaseHandler
    */
   protected $databaseHandler;

    public function __construct()
    {
        $this->databaseHandler = new DatabaseHandler();
    }

    protected function validateEntity(ProductInterface $entity): void
    {
        $patterns = $entity->getPatterns();
        foreach ($entity->asJson() as $key => $value) {
            if($key !== 'type' && $key !== 'id') {
                if (!preg_match($patterns[$key], $value)) {
                    throw new \Exception('Invalid key value: \'' . $value . '\'', ApiConstants::HTTP_BAD_REQUEST);
                }
            }
        }
    }

    /**
     * @param array $parameters parametry body żądania
     * @param array $conditions warunki walidacji w formacie
     * [possibleFieldName => (bool) isRequired, ...]
     * @example ['city' => 'false', 'name' => 'false']
     */
    protected function validateParameters(array $parameters, array $conditions): void
    {
        foreach (array_keys($parameters) as $key) {
            if (!array_key_exists($key, $conditions)) {
                throw new \Exception('Redundant field name: \'' . $key . '\'', ApiConstants::HTTP_BAD_REQUEST);
            }
        }
        foreach ($conditions as $key => $value) {
            if ($value && !array_key_exists($key, $parameters)) {
                throw new \Exception('Missing required field: \'' . $key . '\'', ApiConstants::HTTP_BAD_REQUEST);
            }
        }
    }
}
